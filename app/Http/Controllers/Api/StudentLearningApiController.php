<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseChapterLesson;
use App\Models\CourseProgress;
use App\Models\FollowUpAction;
use App\Models\FollowUpActionResponse;
use App\Models\Quiz;
use App\Traits\ApiResponse;
use App\Traits\HandlesCourseAccess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Cache;
use App\Models\QuizQuestion;
use Illuminate\Http\JsonResponse;

class StudentLearningApiController extends Controller
{
    use ApiResponse, HandlesCourseAccess;

    /**
     * @OA\Get(
     *     path="/student-learning/{slug}",
     *     summary="Get course by slug",
     *     description="Get course by slug",
     *     tags={"Student Learning"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Course slug",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="full-stack-web-development-with-react",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Laravel 8 Course"
     *             ),
     *             @OA\Property(
     *                 property="slug",
     *                 type="string",
     *                 example="laravel-8-course"
     *             ),
     *             @OA\Property(
     *                 property="thumbnail",
     *                 type="string",
     *                 example="https://example.com/image.jpg"
     *             ),
     *             @OA\Property(
     *                 property="chapters",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="Chapter 1"
     *                     ),
     *                     @OA\Property(
     *                         property="chapterItems",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                                 example=1
     *                             ),
     *                             @OA\Property(
     *                                 property="title",
     *                                 type="string",
     *                                 example="Lesson 1"
     *                             ),
     *                             @OA\Property(
     *                                 property="type",
     *                                 type="string",
     *                                 example="lesson"
     *                             ),
     *                             @OA\Property(
     *                                 property="lesson",
     *                                 type="object",
     *                                 @OA\Property(
     *                                     property="id",
     *                                     type="integer",
     *                                     example=1
     *                                 ),
     *                                 @OA\Property(
     *                                     property="title",
     *                                     type="string",
     *                                     example="Lesson 1"
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     )
     * )
     */
    public function index(Request $request, string $slug)
    {

        try {

            $user = $this->getAuthenticatedUser($request);
            if ($user instanceof JsonResponse) return $user;

            $course = $this->getCourseBySlug($slug);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;
            // Kalau sudah lolos, baru ambil data lengkap course
            $course = Course::active()->with([
                'chapters',
                'chapters.chapterItems',
                'chapters.chapterItems.lesson',
                'chapters.chapterItems.quiz',
                'chapters.chapterItems.rtl',
            ])->withTrashed()
                ->where('slug', $slug)
                ->select('courses.id', 'courses.title', 'courses.slug', 'courses.thumbnail')
                ->first();

            return $this->successResponse($course, 'Course fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * Post progress lesson
     *
     * @OA\Post(
     *     path="/student-learning/post-progresslesson",
     *     summary="Post progress lesson",
     *     description="Post progress lesson",
     *     tags={"Student Learning"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Lesson and type",
     *         @OA\JsonContent(
     *             required={"courseId","chapterId","lessonId","type"},
     *             @OA\Property(property="courseId", type="integer", example=1),
     *             @OA\Property(property="chapterId", type="integer", example=1),
     *             @OA\Property(property="lessonId", type="integer", example=1),
     *             @OA\Property(property="type", type="string", example="lesson"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Progress updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Progress updated successfully"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error"),
     *         ),
     *     ),
     * )
     */
    public function postProgresslesson(Request $request)
    {
        $request->validate([
            'courseId' => ['required', 'exists:courses,id'],
            'chapterId' => ['required', 'exists:course_chapters,id'],
            'lessonId' => ['required', 'exists:course_chapter_items,id'],
            'type' => ['required'],
        ]);

        try {

            $user = $this->getAuthenticatedUser($request);
            if ($user instanceof JsonResponse) return $user;

            $course = $this->getCourseById($request->courseId);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            $progress = CourseProgress::where([
                'lesson_id' => $request->lessonId,
                'user_id' => $user->id,
                'type' => $request->type
            ])->first();

            if ($progress) {
                // Cek apakah lesson sebelumnya sudah selesai jika ini adalah lesson
                // if ($request->type == 'lesson') {
                // Cari lesson sebelumnya yang lebih kecil dari lesson_id saat ini
                $previousLesson = CourseProgress::where([
                    'user_id' => $user->id,
                    'course_id' => $progress->course_id,
                    'type' => $request->type,
                ])
                    ->where('lesson_id', '<', $request->lessonId) // lesson_id lebih kecil
                    ->orderBy('lesson_id', 'desc') // Urutkan berdasarkan lesson_id terbesar
                    ->first(); // Ambil yang paling besar yang lebih kecil dari lesson_id

                // Jika ada lesson sebelumnya dan status watched-nya masih 0
                if ($previousLesson && $previousLesson->watched == 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('Please finish the previous lesson first.')
                    ]);
                }

                // Update status watched berdasarkan request status
                if ($progress->watched  == 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('You already watched this lesson')
                    ]);
                }
            }
            CourseProgress::updateOrCreate(
                [
                    'user_id'    => $user->id,
                    'course_id'  => $request->courseId,
                    'chapter_id' => $request->chapterId,
                    'lesson_id'  => $request->lessonId,
                    'type'       => $request->type,
                ],
                ['current' => 1]
            );


            $lesson = [];

            switch ($request->type) {
                case 'lesson':
                    $lesson = CourseChapterLesson::select(['id', 'file_path', 'storage', 'file_type', 'downloadable', 'description'])
                        ->findOrFail($request->lessonId)
                        ->toArray();

                    $lesson['type'] = 'lesson';

                    if (in_array($lesson['storage'], ['wasabi', 'aws'])) {
                        $lesson['file_path'] = Storage::disk($lesson['storage'])
                            ->temporaryUrl($lesson['file_path'], now()->addSeconds(30));
                    }

                    if ($lesson['storage'] === 'upload') {
                        $lesson['file_path'] = $this->generateSecureLink($lesson['file_path']);
                    }

                    break;

                case 'live':
                    $lesson = CourseChapterLesson::with([
                        'course:id,instructor_id,slug',
                        'course.instructor:id',
                        'course.instructor.zoom_credential:id,instructor_id,client_id,client_secret',
                        'course.instructor.jitsi_credential:id,instructor_id,app_id,api_key,permissions',
                        'live:id,lesson_id,start_time,type,meeting_id,password,join_url',
                    ])->select([
                        'id',
                        'course_id',
                        'chapter_item_id',
                        'title',
                        'description',
                        'duration',
                        'file_path',
                        'storage',
                        'file_type',
                        'downloadable'
                    ])->findOrFail($request->lessonId);

                    $lesson = $lesson->toArray();
                    $lesson['type'] = 'live';

                    $now = Carbon::now();
                    $startTime = Carbon::parse($lesson['live']['start_time']);
                    $endTime = $startTime->copy()->addMinutes($lesson['duration']);

                    $lesson['start_time'] = formattedDateTime($startTime);
                    $lesson['end_time'] = formattedDateTime($endTime);

                    $lesson['is_live_now'] = $now->lt($startTime) ? 'not_started' : ($now->between($startTime, $endTime) ? 'started' : 'ended');

                    break;

                case 'document':
                    $lesson = CourseChapterLesson::select(['id', 'file_path', 'storage', 'file_type', 'downloadable', 'description'])
                        ->findOrFail($request->lessonId)
                        ->toArray();
                    $lesson['type'] = 'document';
                    break;

                case 'rtl':
                    $lesson = FollowUpAction::findOrFail($request->lessonId)->toArray();
                    $lesson['type'] = 'rtl';
                    break;

                default: // 'quiz' or others
                    $lesson = Quiz::findOrFail($request->lessonId)->toArray();
                    $lesson['type'] = 'quiz';
                    break;
            }

            return $this->successResponse(['course_item' => $lesson], 'File info retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e, [], 500);
        }
    }

    /**
     * Make lesson complete
     *
     * @OA\Post(
     *     path="/student-learning/make-lesson-complete",
     *     summary="Make lesson complete",
     *     description="Mark a lesson as completed by the student.",
     *     tags={"Student Learning"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Lesson completion payload",
     *         @OA\JsonContent(
     *             required={"lesson_id", "course_id", "type"},
     *             @OA\Property(property="lesson_id", type="integer", example=1, description="ID of the lesson"),
     *             @OA\Property(property="course_id", type="integer", example=1, description="ID of the course"),
     *             @OA\Property(property="type", type="string", example="lesson", description="Type of progress")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Progress updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Progress updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Business logic error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Please finish the previous lesson first.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="lesson_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="The lesson_id field is required.")
     *                 ),
     *                 @OA\Property(
     *                     property="course_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="The course_id field is required.")
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="array",
     *                     @OA\Items(type="string", example="The type field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Progress not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Progress not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred")
     *         )
     *     )
     * )
     */

    public function makeLessonComplete(Request $request)
    {
        $request->validate([
            'lesson_id' => ['required'],
            'course_id' => ['required', 'exists:courses,id'],
            'type' => ['required'],
        ]);

        try {

            $user = $this->getAuthenticatedUser($request);
            if ($user instanceof JsonResponse) return $user;

            $course = $this->getCourseById($request->courseId);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            // Cari progress untuk lesson yang dimaksud
            $progress = CourseProgress::where([
                'lesson_id' => $request->lesson_id,
                'user_id' => $user->id,
                'course_id' => $course->id,
                'type' => $request->type
            ])->first();

            if ($progress) {
                // Cek apakah lesson sebelumnya sudah selesai
                $previousLesson = CourseProgress::where([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'type' => $request->type,
                ])
                    ->where('lesson_id', '<', $request->lesson_id)
                    ->orderBy('lesson_id', 'desc')
                    ->first();

                if ($previousLesson && $previousLesson->watched == 0) {
                    return $this->errorResponse(__('Please finish the previous lesson first.'), [], 400);
                }

                if ($progress->watched == 1) {
                    return $this->errorResponse(__('You already watched this lesson'), [], 400);
                }

                $progress->watched = 1;
                $progress->save();

                return $this->successResponse([], 'Progress updated successfully', 200);
            } else {
                return $this->errorResponse('Progress not found', [], 404);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }


    /**
     * Get quiz questions
     *
     * @OA\Get(
     *     path="/student-learning/{courseId}/quiz/{quizId}",
     *     summary="Get quiz questions",
     *     description="Get quiz questions",
     *     tags={"Student Learning"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="courseId",
     *         in="path",
     *         required=true,
     *         description="Course ID",
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="Quiz ID",
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quiz questions retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="quiz_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="What is the capital of France?"),
     *                 @OA\Property(
     *                     property="answers",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="quiz_question_id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Paris")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Bad request"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Quiz not found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error"),
     *         ),
     *     ),
     * )
     */

    public function quizIndex(Request $request, string $courseId, string $quizId) //method get
    {

        try {

            $user = $this->getAuthenticatedUser($request);
            if ($user instanceof JsonResponse) return $user;

            $course = $this->getCourseById($courseId);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            // Hitung jumlah attempt user
            $attempt = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->count();

            // Ambil quiz dengan jumlah pertanyaan
            $quiz = Quiz::withCount('questions')->findOrFail($quizId);

            // Cek limit attempt
            if (!is_null($quiz->attempt) && $attempt >= $quiz->attempt) {

                return $this->errorResponse(__('You reached maximum attempt'), [], 400);
            }

            // Cek batas waktu
            if (Carbon::parse($quiz->due_date)->isPast()) {
                return $this->errorResponse(__('Batas waktu telah berakhir pada tanggal :date', [
                    'date' => Carbon::parse($quiz->due_date)->toFormattedDateString()
                ]), [], 400);
            }

            // Key cache: unik per user per quiz
            $cacheKey = "quiz_{$quizId}_user_{$user->id}_questions";

            if (Cache::has($cacheKey)) {
                $questions = Cache::get($cacheKey);
            } else {
                // Ambil soal random + jawaban acak
                $questions = $quiz->questions()
                    ->inRandomOrder()
                    ->with(['answers' => function ($query) {
                        $query->inRandomOrder();
                    }])
                    ->get();

                // Simpan cache sesuai due_date
                Cache::put($cacheKey, $questions, Carbon::parse($quiz->due_date));
            }

            // Menyembunyikan jawaban benar (correct) agar tidak bisa diakses dari frontend dan disalahgunakan
            $questions->transform(function ($question) {
                $question->answers = collect($question->answers)->map(function ($answer) {
                    unset($answer['correct']);
                    return $answer;
                });

                return $question;
            });

            $quiz->setRelation('questions', $questions);

            $data = [
                'quiz' => $quiz,
                'attempt' => $attempt,
                'due_date' => $quiz->due_date,
            ];

            return $this->successResponse($data, 'Progress updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * Store quiz answers
     * 
     * @OA\Post(
     *     path="/student-learning/{courseId}/quiz/{quizId}",
     *     summary="Store quiz answers",
     *     description="Store quiz answers",
     *     tags={"Student Learning"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="courseId",
     *         in="path",
     *         required=true,
     *         description="Course ID",
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="Quiz ID",
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Quiz answers submitted by the user",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="question",
     *                 type="object",
     *                 additionalProperties={
     *                     @OA\Property(type="integer", example=5)
     *                 },
     *                 example={"1": 5, "2": 8}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quiz submitted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=12),
     *                 @OA\Property(property="quiz_id", type="integer", example=3),
     *                 @OA\Property(
     *                     property="result",
     *                     type="object",
     *                     additionalProperties={
     *                         @OA\Property(property="answer", type="integer", example=5),
     *                         @OA\Property(property="correct", type="boolean", example=true)
     *                     }
     *                 ),
     *                 @OA\Property(property="user_grade", type="integer", example=85),
     *                 @OA\Property(property="status", type="string", example="pass"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-22T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-22T10:00:00Z")
     *             ),
     *             @OA\Property(property="message", type="string", example="Quiz submitted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not enrolled in this course")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Quiz not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred")
     *         )
     *     )
     * )
     */
    public function quizStore(Request $request, string $quizId)
    {
        $grad = 0;
        $result = [];

        try {
            $quiz = Quiz::find($quizId);

            if (!$quiz) {
                return $this->errorResponse('Quiz not found', [], 404);
            }

            $user = $this->getAuthenticatedUser($request);
            if ($user instanceof JsonResponse) return $user;


            $course = $this->getCourseById($quiz->course_id);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            $attempt = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->count();

            if (!is_null($quiz->attempt) && $attempt >= $quiz->attempt) {

                return $this->errorResponse(__('You reached maximum attempt'), [], 400);
            }

            foreach ($request->question ?? [] as $key => $questionAns) {
                $question = QuizQuestion::findOrFail($key);
                $answer = $question->answers->where('correct', 1)->pluck('id')->toArray();

                if (in_array($questionAns, $answer)) {
                    $grad += $question->grade;
                }
                $result[$key] = [
                    "answer" => $questionAns,
                    "correct" => in_array($questionAns, $answer),
                ];
            }

            QuizResult::create([
                'user_id' => $user->id,
                'quiz_id' => $quizId,
                'result' => $result,
                'user_grade' => $grad,
                'status' => $grad >= $quiz->pass_mark ? 'pass' : 'failed',
            ]);

            $quizResult = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->latest()
                ->first();

            return $this->successResponse($quizResult, 'Quiz submitted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * Get quiz result
     * 
     * @OA\Get(
     *     path="/student-learning/{courseId}/quiz/{quizId}/result",
     *     summary="Get quiz result",
     *     description="Retrieve detailed result of a quiz attempt by a student",
     *     tags={"Student Learning"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="courseId",
     *         in="path",
     *         required=true,
     *         description="course ID",
     *         example=1,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="Quiz ID",
     *         example=1,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="quiz",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="course_id", type="integer", example=3),
     *                     @OA\Property(property="title", type="string", example="Basic Math Quiz"),
     *                     @OA\Property(property="description", type="string", example="Test your basic math skills"),
     *                     @OA\Property(property="pass_mark", type="integer", example=70),
     *                     @OA\Property(property="questions_count", type="integer", example=3),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-20T08:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-21T10:00:00Z")
     *                 ),
     *                 @OA\Property(property="attempt", type="integer", example=2),
     *                 @OA\Property(
     *                     property="quizResult",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=5),
     *                     @OA\Property(property="quiz_id", type="integer", example=1),
     *                     @OA\Property(
     *                         property="result",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="question_id", type="integer", example=1),
     *                             @OA\Property(property="answer", type="integer", example=7),
     *                             @OA\Property(property="correct", type="boolean", example=true)
     *                         )
     *                     ),
     *                     @OA\Property(property="user_grade", type="integer", example=80),
     *                     @OA\Property(property="status", type="string", example="pass"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-22T09:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-22T09:00:00Z")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Data retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz or result not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Quiz not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred")
     *         )
     *     )
     * )
     */

    function quizResult(Request $request, string $courseId, string $quizId)
    {
        try {

            $user = $this->getAuthenticatedUser($request);
            if ($user instanceof JsonResponse) return $user;

            $course = $this->getCourseById($courseId);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            $attempt = QuizResult::where('user_id', $user->id)->where('quiz_id', $quizId)->count();

            $quiz = Quiz::withCount('questions')->findOrFail($quizId);

            $quizResult = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->latest()
                ->first();
            $data = [
                'quiz' => $quiz,
                'attempt' => $attempt,
                'quizResult' => $quizResult
            ];
            return $this->successResponse($data, 'Data retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/student-learning/{courseId}/rtl/{rtlId}",
     *     summary="Get RTL item",
     *     description="Get RTL item",
     *     tags={"Student Learning"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Course ID",
     *         in="path",
     *         name="courseId",
     *         required=true,
     *         example="1",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         description="RTL ID",
     *         in="path",
     *         name="rtlId",
     *         required=true,
     *         example="1",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="item",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Tugas Mandiri Modul 1"),
     *                 @OA\Property(property="description", type="string", example="Kerjakan soal terkait pembelajaran mandiri"),
     *                 @OA\Property(property="start_date", type="string", format="date-time", example="2025-04-21T08:00:00Z"),
     *                 @OA\Property(property="due_date", type="string", format="date-time", example="2025-04-25T23:59:59Z")
     *             ),
     *             @OA\Property(
     *                 property="response",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="participant_response", type="string", example="Saya telah menyelesaikan tugas dengan baik."),
     *                 @OA\Property(property="participant_file", type="string", format="uri", example="https://storage.example.com/uploads/file.pdf"),
     *                 @OA\Property(property="instructor_response", type="string", example="Sudah bagus, lanjutkan."),
     *                 @OA\Property(property="score", type="number", format="float", example=85.5),
     *                 @OA\Property(property="follow_up_action_id", type="integer", example=1),
     *                 @OA\Property(property="participant_id", type="integer", example=5),
     *                 @OA\Property(property="instructor_id", type="integer", example=2)
     *             ),
     *             @OA\Property(property="message", type="string", example="Data retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */

    public function rtlIndex(Request $request, string $courseId, string $rtlId)
    {
        try {

            $user = $this->getAuthenticatedUser($request);
            if ($user instanceof JsonResponse) return $user;

            $course = $this->getCourseById($courseId);
            if ($course instanceof JsonResponse) return $course;

            $enrolled = $this->checkEnrollment($user, $course);
            if ($enrolled instanceof JsonResponse) return $enrolled;

            $item = FollowUpAction::find($rtlId);
            if (!$item) {
                return $this->errorResponse('Data Not found', [], 404);
            }

            $response = FollowUpActionResponse::where('follow_up_action_id', $item->id)
                ->where('participant_id', $user->id)
                ->first();

            // Belum dimulai
            if (Carbon::parse($item->start_date)->isFuture()) {
                return $this->errorResponse(__('Belum dimulai pada tanggal :date', [
                    'date' => Carbon::parse($item->start_date)->toFormattedDateString()
                ]), [], 403);
            }

            // Sudah lewat due date
            if (Carbon::parse($item->due_date)->isPast()) {
                return $this->errorResponse(__('Batas waktu telah berakhir pada tanggal :date', [
                    'date' => Carbon::parse($item->due_date)->toFormattedDateString()
                ]), [], 403);
            }

            return $this->successResponse([
                'rtl' => $item,
                'response_rtl' => $response,
            ], 'Data retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     *  @OA\Post(
     *     path="/student-learning/{courseId}/rtl/{rtlId}",
     *     summary="Save RTL item response",
     *     description="Save RTL item response",
     *     tags={"Student Learning"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Course ID",
     *         in="path",
     *         name="courseId",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="RTL ID",
     *         in="path",
     *         name="rtlId",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"summary","file_path"},
     *                 @OA\Property(
     *                     property="summary",
     *                     type="string",
     *                     description="Summary"
     *                 ),
     *                 @OA\Property(
     *                     property="file_path",
     *                     type="string",
     *                     format="binary",
     *                     description="File to upload"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Rencana tindak lanjut berhasil disimpan."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *         ),
     *     )
     * )
     */

    public function rtlStore(Request $request, string $courseId, string $rtlId)
    {
        $user = $this->getAuthenticatedUser($request);
        if ($user instanceof JsonResponse) return $user;

        $course = $this->getCourseById($courseId);
        if ($course instanceof JsonResponse) return $course;

        $enrolled = $this->checkEnrollment($user, $course);
        if ($enrolled instanceof JsonResponse) return $enrolled;

        // Cek apakah sudah ada response sebelumnya
        $response = FollowUpActionResponse::where('follow_up_action_id', $rtlId)
            ->where('participant_id', $user->id)
            ->first();

        // Kalau belum ada, buat baru
        if (!$response) {
            $response = new FollowUpActionResponse;
            $response->follow_up_action_id = $rtlId;
            $response->participant_id = $user->id;
        }

        // Validasi request tergantung kondisi create/update
        $rules = [
            'summary' => $response->exists ? 'sometimes' : 'required',
            'file_path' => $response->exists ? 'sometimes|mimes:pdf|max:30720' : 'required|mimes:pdf|max:30720',
        ];

        $messages = [
            'summary.required' => 'Ringkasan harus diisi',
            'file_path.required' => 'Wajib mengunggah file PDF',
            'file_path.sometimes' => 'File opsional, jika ada harus berupa PDF',
            'file_path.mimes' => 'File harus berupa PDF',
            'file_path.max' => 'Ukuran file maksimal 30 MB',
        ];

        $request->validate($rules, $messages);

        // Simpan summary
        if ($request->filled('summary')) {
            $response->participant_response = $request->summary;
        }

        // Proses file jika ada
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-]/', '-', $originalName);
            $extension = $file->getClientOriginalExtension();

            $fileName = 'rtl/' . Str::slug($user->name) . $user->id . '-' . $sanitizedName . '.' . $extension;
            $file->storeAs('private', $fileName, 'local');

            $response->participant_file = Str::slug($user->name) . $user->id . '-' . $sanitizedName . '.' . $extension;
        }

        // Simpan response
        if ($response->save()) {
            return $this->successResponse($response, 'Rencana tindak lanjut berhasil disimpan.', 200);
        }

        return $this->errorResponse('Rencana tindak lanjut gagal disimpan.', [], 500);
    }
}
