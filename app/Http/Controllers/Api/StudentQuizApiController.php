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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

            // 🔹 Cek due date
            if ($quiz->due_date && now()->greaterThan($quiz->due_date)) {
                return $this->errorResponse('Quiz has expired.', [], 403);
            }

            // 🔹 Hitung attempt
            $attemptCount = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->count();

            // 🔹 Ambil session terakhir
            $lastSession = QuizSession::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->latest()
                ->first();

            // 🔹 Kalau ada session aktif, langsung kembalikan
            $activeSession = QuizSession::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->whereNull('ended_at')
                ->first();

            if ($activeSession) {
                return $this->successResponse([
                    'questions'   => $activeSession->questions,
                    'answers'     => $activeSession->answers,
                    'started_at'  => $activeSession->started_at,
                    'ended_at'    => $activeSession->ended_at,
                    'time_limit'  => $quiz->time,
                    'due_date'    => $quiz->due_date,
                    'attempts'    => $attemptCount,
                    'max_attempt' => $quiz->attempt,
                ], 'Quiz already started', 200);
            }

            // 🔹 Kalau attempt habis (kecuali 0 = unlimited)
            if ($quiz->attempt !== 0 && $attemptCount >= $quiz->attempt) {
                return $this->successResponse([
                    'questions'   => $lastSession?->questions,
                    'answers'     => $lastSession?->answers,
                    'started_at'  => $lastSession?->started_at,
                    'ended_at'    => $lastSession?->ended_at,
                    'time_limit'  => $quiz->time,
                    'due_date'    => $quiz->due_date,
                    'attempts'    => $attemptCount,
                    'max_attempt' => $quiz->attempt,
                ], "Maximum attempt ({$quiz->attempt}) reached.", 403);
            }

            // 🔹 Kalau masih bisa attempt → buat pertanyaan random
            $randomQuestions = $quiz->questions->shuffle()->map(function ($q) {
                return [
                    'id'      => $q->id,
                    'title'   => $q->title,
                    'type'    => $q->type,
                    'grade'   => $q->grade,
                    'answers' => $q->answers->shuffle()->map(fn($a) => [
                        'id'    => $a->id,
                        'title' => $a->title,
                    ]),
                ];
            });

            // 🔹 Copy jawaban dari session pertama (kalau ada)
            $firstSession = QuizSession::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->orderBy('id', 'asc')
                ->first();

            $answersToCopy = $firstSession?->answers ?? [];

            // 🔹 Update atau buat session baru
            $session = QuizSession::updateOrCreate(
                ['user_id' => $user->id, 'quiz_id' => $quizId],
                [
                    'questions'  => $randomQuestions,
                    'answers'    => $answersToCopy,
                    'started_at' => now(),
                    'ended_at'   => null,
                ]
            );

            return $this->successResponse([
                'questions'   => $session->questions,
                'answers'     => $answersToCopy,
                'started_at'  => $session->started_at,
                'ended_at'    => $session->ended_at,
                'time_limit'  => $quiz->time,
                'due_date'    => $quiz->due_date,
                'attempts'    => $attemptCount + 1,
                'max_attempt' => $quiz->attempt,
            ], 'Quiz started', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/student-quiz/quizzes/{quizId}/save-answer",
     *     summary="Simpan jawaban sementara",
     *     description="Menyimpan jawaban sementara ke tabel quiz_sessions",
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
     *             type="object",
     *             @OA\Property(
     *                 property="answers",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="question_id", type="integer", example=496),
     *                     @OA\Property(property="answer_id", type="integer", example=1975)
     *                 ),
     *                 example={
     *                     {
     *                         "question_id": 496,
     *                         "answer_id": 1975
     *                     }
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Jawaban disimpan"),
     *     @OA\Response(response=404, description="Quiz/Season not found"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function saveAnswer(Request $request, $quizId)
    {
        try {
            $quiz = Quiz::with('questions.answers')->find($quizId);
            if (!$quiz) {
                return $this->errorResponse('Quiz not found', [], 404);
            }

            $user = $request->user();
            if ($user instanceof JsonResponse) return $user;

            $courseId = $quiz->course_id;
            $course = $this->getCourseById($courseId);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            if (now() > $quiz->due_date) {
                return $this->errorResponse('Quiz sudah selesai, Anda tidak dapat mengumpulkan jawaban lagi', [], 403);
            }

            $attempt = QuizResult::where('user_id', $user->id)->where('quiz_id', $quizId)->count();

            if ($attempt >= $quiz->attempt) {
                return $this->errorResponse('Anda telah mencapai batas pengumpulan jawaban', [], 403);
            }

            // Validasi struktur request
            $validated = $request->validate([
                'answers' => 'required|array',
                'answers.*.question_id' => 'required|integer',
                'answers.*.answer_id'   => 'required|integer',
            ]);

            $incomingAnswers = $validated['answers'];

            // Buat session kalau belum ada
            $season = QuizSession::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'quiz_id' => $quizId,
                ],
                [
                    'questions'  => $quiz->questions->pluck('id')->toArray(),
                    'answers'    => [],
                    'started_at' => now(),
                ]
            );

            // Ambil jawaban lama
            $existingAnswers = $season->answers ?? [];

            foreach ($incomingAnswers as $newAnswer) {
                $questionId = $newAnswer['question_id'];
                $answerId   = $newAnswer['answer_id'];

                // Pastikan question_id valid untuk quiz ini
                $question = $quiz->questions->firstWhere('id', $questionId);
                if (!$question) {
                    return response()->json([
                        'code' => 422,
                        'status' => false,
                        'message' => "Gagal validasi: question_id {$questionId} tidak valid",
                        'errors' => [
                            'question_id' => ['question_id tidak valid'],
                        ],
                        'data' => [],
                    ], 422);
                }

                // Pastikan answer_id valid untuk question ini
                $validAnswer = $question->answers->firstWhere('id', $answerId);
                if (!$validAnswer) {
                    return response()->json([
                        'code' => 422,
                        'status' => false,
                        'message' => "Gagal validasi: answer_id {$answerId} tidak valid untuk question_id {$questionId}",
                        'errors' => [
                            'answer_id' => ['answer_id tidak valid untuk question ini'],
                        ],
                        'data' => [],
                    ], 422);
                }

                // Update atau tambah jawaban
                $found = false;
                foreach ($existingAnswers as &$ans) {
                    if ($ans['question_id'] == $questionId) {
                        $ans['answer_id'] = $answerId;
                        $found = true;
                        break;
                    }
                }
                unset($ans);

                if (!$found) {
                    $existingAnswers[] = $newAnswer;
                }
            }

            $season->answers = $existingAnswers;
            $season->save();

            return response()->json([
                'message' => 'Jawaban disimpan.',
                'answers' => $season->answers,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
                'data' => []
            ], 422);
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
            if (!$quiz) {
                return $this->errorResponse('Quiz not found', [], 404);
            }

            // 🔹 Cek due date
            if ($quiz->due_date && now()->greaterThan($quiz->due_date)) {
                return $this->errorResponse('Quiz has expired.', [], 403);
            }

            // 🔹 Ambil session aktif terakhir
            $session = QuizSession::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->whereNull('ended_at')
                ->latest('started_at')
                ->first();

            if (!$session) {
                return $this->errorResponse('No active session found.', [], 404);
            }

            // 🔹 Cek batas waktu pengerjaan
            $maxTime    = $session->started_at->addMinutes((int) $quiz->time);
            $waktuHabis = now()->gt($maxTime);

            $userAnswers = collect($session->answers ?? [])->keyBy('question_id');
            $questions   = $quiz->questions;

            $score      = 0;
            $totalMark  = 0;
            $resultData = [];

            foreach ($questions as $question) {
                $correctAnswerIds = $question->answers
                    ->where('correct', true)
                    ->pluck('id')
                    ->sort()
                    ->values();

                $userAnswer = $userAnswers->get($question->id);

                if ($userAnswer) {
                    $userAnswerIds = collect([$userAnswer['answer_id']])->sort()->values();
                    $answerValue   = $userAnswer['answer_id'];
                } else {
                    $userAnswerIds = collect();
                    $answerValue   = null;
                }

                $isCorrect = $correctAnswerIds->toArray() === $userAnswerIds->toArray();

                if ($isCorrect) {
                    $score += $question->grade; // skor asli
                }

                $totalMark += $question->grade;

                $resultData[$question->id] = [
                    'answer'  => $answerValue,
                    'correct' => $isCorrect,
                ];
            }

            // 🔹 Tutup session
            $endedAt = now();
            $session->update(['ended_at' => $endedAt]);

            $durationInSeconds = $endedAt->diffInSeconds($session->started_at);

            // 🔹 Simpan hasil quiz → selalu insert (bisa jadi history)
            $quizResult = QuizResult::create([
                'user_id'    => $user->id,
                'quiz_id'    => $quiz->id,
                'result'     => $resultData,
                'user_grade' => $score, // skor asli
                'status'     => $score >= $quiz->pass_mark ? 'passed' : 'failed',
                'start_quiz_at' => $session->started_at,
                'end_quiz_at' => $endedAt,
                'duration'   => abs($durationInSeconds),
            ]);

            // 🔹 Hitung attempt yang sudah dipakai (dari QuizResult)
            $attemptCount = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->count();

            return $this->successResponse([
                'message'            => 'Quiz berhasil disubmit' . ($waktuHabis ? ' (melebihi batas waktu)' : ''),
                'score'              => $quizResult->user_grade,
                'status'             => $quizResult->status,
                'late'               => $waktuHabis,
                'duration'           => $quizResult->duration,
                'duration_formatted' => gmdate('H:i:s', $quizResult->duration),
                'result'             => $quizResult->result,
                'attempts'           => $attemptCount,
                'max_attempt'        => $quiz->attempt,
            ], 'Quiz submitted successfully', 200);
        } catch (\Exception $e) {
            Log::error('Quiz Submit Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/student-quiz/quizzes/{quizId}/my-quiz-results",
     *     summary="Lihat hasil kuis peserta",
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
     *         description="Daftar hasil kuis",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function myResults(Request $request, string $quizId)
    {
        try {
            $user = $request->user();
            if ($user instanceof JsonResponse) return $user;

            $result = QuizResult::with('quiz')
                ->where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->latest()
                ->first();

            if (!$result) {
                return $this->errorResponse('No result found', [], 404);
            }

            $formatted = [
                'id'         => $result->id,
                'score'      => $result->user_grade,
                'status'     => ucfirst($result->status),
                'duration'   => $result->duration . ' detik',
                'created_at' => $result->created_at->format('d M Y H:i'),
                'answers'    => collect($result->result)->map(function ($res, $qid) {
                    return [
                        'question_id' => (int) $qid,
                        'answer'      => $res['answer'],
                        'is_correct'  => $res['correct'],
                    ];
                })->values(),
                'quiz' => [
                    'id'         => $result->quiz->id,
                    'title'      => $result->quiz->title,
                    'time_limit' => $result->quiz->time . ' menit',
                    'attempts'   => $result->quiz->attempt,
                    'pass_mark'  => $result->quiz->pass_mark,
                    'total_mark' => $result->quiz->total_mark,
                    'due_date'   => $result->quiz->due_date,
                ]
            ];

            return $this->successResponse($formatted, 'Result fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}