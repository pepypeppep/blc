<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\QuizSession;
use App\Traits\ApiResponse;
use App\Traits\HandlesCourseAccess;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Student Quiz",
 *     description="API untuk peserta mengikuti kuis"
 * )
 */
class StudentQuizApiController extends Controller
{
    use ApiResponse, HandlesCourseAccess;

    /**
     * @OA\Get(
     *     path="/student-quiz/quizzes/{quizId}/start",
     *     summary="Mulai kuis (acak pertanyaan & jawaban)",
     *     description="Jika sudah dimulai sebelumnya, akan mengembalikan season yang sama.",
     *     tags={"Student Quiz"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID kuis",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kuis dimulai",
     *         @OA\JsonContent(
     *             @OA\Property(property="questions", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="answers", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="started_at", type="string"),
     *             @OA\Property(property="ended_at", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Quiz not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function start(Request $request, $quizId)
    {
        try {
            $quiz = Quiz::with('questions.answers')->find($quizId);
            if (!$quiz) return $this->errorResponse('Quiz not found', [], 404);

            $user = $request->user();
            if ($user instanceof JsonResponse) return $user;

            $course = $this->getCourseById($quiz->course_id);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            // Cek due date
            if ($quiz->due_date && now()->greaterThan($quiz->due_date)) {
                return $this->errorResponse('Quiz has expired.', [], 403);
            }

            // Hitung jumlah percobaan
            $attemptCount = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->count();

            if ($quiz->attempt && $attemptCount >= $quiz->attempt) {
                return $this->errorResponse('Maximum attempt reached.', [], 403);
            }

            // Cek jika sudah pernah mulai
            $season = QuizSession::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->whereNull('ended_at')
                ->first();

            if ($season) {
                return $this->successResponse([
                    'questions' => $season->questions,
                    'answers' => $season->answers,
                    'started_at' => $season->started_at,
                    'ended_at' => $season->ended_at,
                    'time_limit' => $quiz->time,
                    'due_date' => $quiz->due_date,
                ], 'Quiz already started', 200);
            }

            // Acak soal dan jawaban
            $randomQuestions = $quiz->questions->shuffle()->map(function ($q) {
                return [
                    'id' => $q->id,
                    'title' => $q->title,
                    'type' => $q->type,
                    'grade' => $q->grade,
                    'answers' => $q->answers->shuffle()->map(fn($a) => [
                        'id' => $a->id,
                        'title' => $a->title,
                    ])
                ];
            });

            // Simpan session baru
            $season = QuizSession::create([
                'user_id' => $user->id,
                'quiz_id' => $quizId,
                'questions' => $randomQuestions,
                'started_at' => now(),
            ]);

            return $this->successResponse([
                'questions' => $randomQuestions,
                'answers' => [],
                'started_at' => $season->started_at,
                'ended_at' => null,
                'time_limit' => $quiz->time,
                'due_date' => $quiz->due_date,
            ], 'Quiz started', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/student-quiz/quizzes/{quizId}/save-answer",
     *     summary="Simpan jawaban sementara",
     *     description="Menyimpan jawaban sementara ke tabel quiz_seasons",
     *     tags={"Student Quiz"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID kuis",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="answers",
     *                 type="object",
     *                 example={"1": {2}, "2": {5,6}}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Jawaban disimpan"),
     *     @OA\Response(response=404, description="Quiz/Season not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function saveAnswer(Request $request, $quizId)
    {

        try {

            $quiz = Quiz::find($quizId);

            if (!$quiz) {
                return $this->errorResponse('Quiz not found', [], 404);
            }

            $user = $request->user();
            if ($user instanceof JsonResponse) return $user;

            $quiz = Quiz::with('questions.answers')->findOrFail($quizId);

            $courseId = $quiz->course_id;

            $course = $this->getCourseById($courseId);

            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            $season = QuizSession::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->first();
            if (!$season) {
                return $this->errorResponse('Season not found', [], 404);
            }

            $season->update(['answers' => $request->answers]);

            return response()->json(['message' => 'Jawaban sementara disimpan.']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/student-quiz/quizzes/{quizId}/submit",
     *     summary="Submit hasil kuis",
     *     description="Hitung nilai dan simpan hasil ke QuizResult. Validasi waktu juga dilakukan.",
     *     tags={"Student Quiz"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID kuis",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kuis disubmit dengan sukses",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="score", type="integer"),
     *             @OA\Property(property="status", type="string", example="passed")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Waktu kuis habis"),
     *     @OA\Response(response=400, description="Kuis sudah disubmit"),
     *     @OA\Response(response=404, description="Quiz not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function submit(Request $request, $quizId)
    {
        try {
            $user = $request->user();
            if ($user instanceof JsonResponse) return $user;

            $quiz = Quiz::with('questions.answers')->find($quizId);
            if (!$quiz) return $this->errorResponse('Quiz not found', [], 404);

            // Cek due date
            if ($quiz->due_date && now()->greaterThan($quiz->due_date)) {
                return $this->errorResponse('Quiz has expired.', [], 403);
            }

            $course = $this->getCourseById($quiz->course_id);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            $season = QuizSession::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->whereNull('ended_at')
                ->first();

            if (!$season) return $this->errorResponse('Season not found', [], 404);

            if ($season->ended_at) {
                return $this->errorResponse('Kuis sudah disubmit.', [], 400);
            }

            $maxTime = $season->started_at->addMinutes((int) $quiz->time);
            $waktuHabis = now()->gt($maxTime);

            $userAnswers = collect($season->answers ?? []);
            $questions = $quiz->questions;

            $totalGrade = 0;
            $totalMark = 0;

            foreach ($questions as $question) {
                $correctAnswerIds = $question->answers->where('correct', true)->pluck('id')->sort()->values();
                $userAnswerIds = collect($userAnswers->get($question->id, []))->sort()->values();

                if ($correctAnswerIds->toArray() === $userAnswerIds->toArray()) {
                    $totalGrade += $question->grade;
                }

                $totalMark += $question->grade;
            }

            QuizResult::updateOrCreate(
                ['user_id' => $user->id, 'quiz_id' => $quiz->id],
                [
                    'user_grade' => $totalGrade,
                    'result' => $userAnswers,
                    'status' => $totalGrade >= $quiz->pass_mark ? 'passed' : 'failed',
                ]
            );

            $season->update(['ended_at' => now()]);

            return $this->successResponse([
                'message' => 'Quiz berhasil disubmit' . ($waktuHabis ? ' (melebihi batas waktu)' : ''),
                'score' => $totalGrade,
                'status' => $totalGrade >= $quiz->pass_mark ? 'passed' : 'failed',
                'late' => $waktuHabis,
            ], 'Quiz submitted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/student-quiz/my-quiz-results",
     *     summary="Lihat hasil kuis peserta",
     *     tags={"Student Quiz"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar hasil kuis",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function myResults(Request $request)
    {
        try {
            $user = $request->user();
            if ($user instanceof JsonResponse) return $user;

            $results = QuizResult::with('quiz')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return $this->successResponse($results, 'Results fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/student-quiz/my-quiz-seasons",
     *     summary="Daftar kuis yang sudah dimulai (quiz_seasons)",
     *     tags={"Student Quiz"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar seasons yang sudah dimulai",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function myQuizSessions(Request $request)
    {
        try {
            $user = $request->user();
            if ($user instanceof JsonResponse) return $user;
            $seasons = QuizSession::with('quiz')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return $this->successResponse($seasons, 'Seasons fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}