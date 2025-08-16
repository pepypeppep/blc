<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CertificateApiController;
use App\Http\Controllers\Api\CoachApiController;
use App\Http\Controllers\Api\CoacheeApiController;
use App\Http\Controllers\Api\InstructorEvaluationController;
use App\Http\Controllers\Api\MenteeApiController;
use App\Http\Controllers\Api\MentorApiController;
use App\Http\Controllers\Api\PendidikanLanjutanController;
use App\Http\Controllers\Api\StudentLearningApiController;
use App\Http\Controllers\Api\StudentQuizApiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\SSOController;

Route::middleware('auth:sso-api')->get('/hello', function (Request $request) {
    return [
        'message' => sprintf("Hello my username is %s", $request->user()->username)
    ];
});

Route::middleware('auth:sso-api')->get('/whoami', [SSOController::class, 'whoami'])->name('whoami');
Route::middleware('auth:sso-api')->get('/users', [UserController::class, 'index'])->name('users.index');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-and-conditions');

    Route::post('/update-device-token', [NotificationController::class, 'updateDeviceToken'])->name('firebase.update.token');
    Route::get('/notifications', [NotificationController::class, 'list'])->name('notification.list');
    Route::get('/notifications/read', [NotificationController::class, 'read'])->name('notification.read');

    // Course
    Route::get('/courses', [CourseApiController::class, 'courses'])->name('courses');
    Route::get('/courses/{slug}', [CourseApiController::class, 'showCourse'])->name('courses.show');
    Route::get('/courses/{slug}/learning', [CourseApiController::class, 'learningCourse'])->name('courses.learning');
    Route::get('/courses/{courseId}/get-thumbnail', [CourseApiController::class, 'getCourseThumbnail'])->name('courses.get-thumbnail');
    Route::get('/courses-popular', [CourseApiController::class, 'popularCourses'])->name('courses.popular');
    Route::get('/courses-favourite', [CourseApiController::class, 'favouriteCourses'])->name('courses.favourite');
    Route::get('/courses-categories', [CourseApiController::class, 'categories'])->name('courses.categories');
    Route::get('/courses-child-categories', [CourseApiController::class, 'childCategories'])->name('courses.categories.child');
    Route::get('/courses-levels', [CourseApiController::class, 'levels'])->name('courses.levels');
    Route::get('/courses/{slug}/reviews', [CourseApiController::class, 'reviews'])->name('courses.reviews');
    Route::post('/courses/{slug}/reviews', [CourseApiController::class, 'reviewsStore'])->name('courses.reviews.store');
    Route::get('/courses/{slug}/questions', [CourseApiController::class, 'questions'])->name('courses.questions');
    Route::post('/courses/{slug}/join', [CourseApiController::class, 'joinCourse'])->name('courses.join.store');
    Route::get('/courses/{slug}/tos', [CourseApiController::class, 'courseTos'])->name('courses.tos');
    Route::post('/courses/{slug}/accept-tos', [CourseApiController::class, 'acceptTos'])->name('courses.accept-tos');

    Route::get('/courses/{type}/{id}/get-file', [StudentLearningApiController::class, 'getFilePathUrl'])->name('courses.get-file');

    //student learning
    Route::prefix('courses')
        ->name('courses.')
        ->middleware('auth:sso-api')
        ->group(function () {
            Route::get('/{course}/instructor-evaluations', [InstructorEvaluationController::class, 'index'])->name('instructorevaluation.index');
            Route::post('/{course}/instructor-evaluations', [InstructorEvaluationController::class, 'store'])->name('instructorevaluation.store');
        });

    // Lesson
    Route::name('lessons.')->group(function () {
        Route::get('/lessons/{slug}/questions', [CourseApiController::class, 'lessonQuestions'])->name('questions');
        Route::post('/lessons/questions-store', [CourseApiController::class, 'questionsStore'])->name('questions.store');
        Route::post('/lessons/answer-store', [CourseApiController::class, 'answerStore'])->name('answer.store');
    });

    // Dashboard
    Route::name('dashboard.')->group(function () {
        Route::get('/certificates', [CertificateApiController::class, 'getCertificatesForStudent'])->name('certificates');

        Route::prefix('pendidikan-lanjutan')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'index']);
            Route::get('/riwayat', [PendidikanLanjutanController::class, 'history']);
            Route::get('/{id}', [PendidikanLanjutanController::class, 'show']);
            Route::get('/{id}/logs', [PendidikanLanjutanController::class, 'logs']);
        });
    });

    // Article
    Route::name('article.')->group(function () {
        Route::get('/articles', [ArticleController::class, 'index'])->name('index');
        Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('show');
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('delete');
        Route::get('/articles-popular', [ArticleController::class, 'popularArticles'])->name('popular');
        Route::get('/articles-tags', [ArticleController::class, 'articleTags'])->name('tags');
        Route::get('/articles-reviews/{id}', [ArticleController::class, 'articleReviews'])->name('reviews');
        Route::group(['middleware' => ['auth:sso-api']], function () {
            Route::post('/articles', [ArticleController::class, 'store'])->name('store');
            Route::post('/articles/{slug}/submit', [ArticleController::class, 'submit'])->name('submit');
            Route::get('/articles/course/enrolled', [ArticleController::class, 'getEnrollments'])->name('getEnrollments');
            Route::get('/articles/{slug}/edit', [ArticleController::class, 'edit'])->name('edit');
            Route::post('/articles/{slug}/update', [ArticleController::class, 'update'])->name('update');
            Route::post('/articles-rating/{id}', [ArticleController::class, 'storeRating'])->name('reviews.store.rating');
            Route::post('/articles-reviews/{id}', [ArticleController::class, 'storeReviews'])->name('reviews.store.review');
        });
    });

    //student learning
    Route::prefix('student-learning')
        ->name('student-learning.')
        ->middleware('auth:sso-api')
        ->group(function () {
            // GET: /api/student-learning/{courseId}/quiz/{quizId}/result
            // Route::get('/{courseId}/quiz/{quizId}/result', [StudentLearningApiController::class, 'quizResult'])->name('quiz.result');
            // // GET: /api/student-learning/{courseId}/quiz/{quizId}
            // Route::get('/{courseId}/quiz/{quizId}', [StudentLearningApiController::class, 'quizIndex'])->name('quiz.index');
            // // POST: /api/student-learning/{courseId}/quiz/{quizId}
            // Route::post('/{courseId}/quiz/{quizId}', [StudentLearningApiController::class, 'quizStore'])->name('quiz.store');

            // GET: /api/student-learning/{courseId}/rtl/{rtlId}
            Route::get('/{courseId}/rtl/{rtlId}', [StudentLearningApiController::class, 'rtlIndex'])->name('rtl.index');
            // POST: /api/student-learning/{courseId}/rtl/{rtlId}
            Route::post('/{courseId}/rtl/{rtlId}', [StudentLearningApiController::class, 'rtlStore'])->name('rtl.store');

            // POST: /api/student-learning/post-progresslesson
            Route::post('/post-progresslesson', [StudentLearningApiController::class, 'postProgresslesson'])->name('post-progresslesson');
            // POST: /api/student-learning/make-lesson-complete
            Route::post('/make-lesson-complete', [StudentLearningApiController::class, 'makeLessonComplete'])->name('make-lesson-complete');

            // GET: /api/student-learning/{slug}
            Route::get('/{slug}', [StudentLearningApiController::class, 'index'])->name('index');
        });

    Route::prefix('student-quiz')
        ->name('student-quiz.')
        ->middleware('auth:sso-api')
        ->group(function () {
            Route::get('quizzes/{quizId}/start', [StudentQuizApiController::class, 'start'])->name('start');        // mulai kuis
            Route::post('quizzes/{quizId}/save-answer', [StudentQuizApiController::class, 'saveAnswer'])->name('answer'); // autosave
            Route::post('quizzes/{quizId}/submit', [StudentQuizApiController::class, 'submit'])->name('submit');     // submit akhir
            Route::get('my-quiz-results', [StudentQuizApiController::class, 'myResults'])->name('my.results');      // hasil kuis
            Route::get('my-quiz-seasons', [StudentQuizApiController::class, 'myQuizSeasons'])->name('my.quiz.seasons');  // daftar kuis yg dirandom
        });

    // Mentoring
    Route::prefix('mentoring')
        ->name('mentoring.')
        ->group(function () {
            Route::prefix('mentee')
                ->name('mentee.')
                ->middleware('auth:sso-api')
                ->group(function () {
                    Route::get('/', [MenteeApiController::class, 'index'])->name('index');
                    Route::get('/{id}', [MenteeApiController::class, 'show'])->name('show');
                    Route::post('/', [MenteeApiController::class, 'store'])->name('store');
                    Route::post('/update-session', [MenteeApiController::class, 'updateSession'])->name('update.session');
                    Route::post('/{id}/submit-approval', [MenteeApiController::class, 'submitForApproval'])->name('submitForApproval');
                    Route::post('/{id}/final-report', [MenteeApiController::class, 'updateFinalReport'])->name('update.final.report');
                    Route::post('/{id}/store-feedback', [MenteeApiController::class, 'feedbackStore'])->name('store.feedback');
                });
            Route::prefix('mentor')
                ->name('mentor.')
                ->middleware('auth:sso-api')
                ->group(function () {
                    Route::get('/', [MentorApiController::class, 'index'])->name('index');
                    Route::get('/{id}', [MentorApiController::class, 'show'])->name('show');
                    Route::post('/', [MentorApiController::class, 'store'])->name('store');
                    Route::put('/{id}/approve', [MentorApiController::class, 'approve'])->name('approve');
                    Route::post('/{id}/reject', [MentorApiController::class, 'reject'])->name('reject');
                    Route::post('/{id}/review', [MentorApiController::class, 'review'])->name('review');
                    Route::get('/{id}/evaluasi', [MentorApiController::class, 'evaluasi'])->name('evaluasi');
                    Route::post('/{id}/store-evaluation', [MentorApiController::class, 'evaluasiStore'])->name('store-evaluation');
                    Route::post('/update-session', [MentorApiController::class, 'updateSession'])->name('update.session');
                    Route::post('/{id}/final-report', [MentorApiController::class, 'updateFinalReport'])->name('update.final.report');
                });
            Route::get('/{id}/{type}', [MenteeApiController::class, 'showDocument'])->name('show.document');
            Route::get('/{id}/{type}/session', [MenteeApiController::class, 'showDocumentSession'])->name('show.document.session');
        });

    // Coaching
    Route::prefix('coaching')
        ->name('coaching.')
        ->group(function () {
            Route::prefix('coachee')
                ->name('coachee.')
                ->middleware('auth:sso-api')
                ->group(function () {
                    Route::get('/', [CoacheeApiController::class, 'index'])->name('index');
                    Route::get('/{id}', [CoacheeApiController::class, 'show'])->name('show');
                    Route::post('/{id}/approval', [CoacheeApiController::class, 'approval'])->name('approval');
                    Route::post('/update-session', [CoacheeApiController::class, 'update'])->name('update.session');
                    Route::post('/submit-report', [CoacheeApiController::class, 'submitReport'])->name('submit-report');
                    Route::post('/submit-final-report/{coachingId}', [CoacheeApiController::class, 'submitFinalReport'])->name('submit-final-report');
                });
            Route::prefix('coach')
                ->name('coach.')
                ->middleware('auth:sso-api')
                ->group(function () {
                    Route::get('/', [CoachApiController::class, 'index'])->name('index');
                    Route::get('/{id}', [CoachApiController::class, 'show'])->name('show');
                    Route::post('/', [CoachApiController::class, 'store'])->name('store');
                    Route::put('/{id}/initiate-consensus', [CoachApiController::class, 'initiateConsensus'])->name('initiate-consensus');
                    Route::put('/{id}/process-coaching', [CoachApiController::class, 'processCoaching'])->name('process-coaching');
                    Route::put('/review', [CoachApiController::class, 'reviewStore'])->name('review');
                    Route::get('/{id}/assessment/{coacheeId}', [CoachApiController::class, 'assessment'])->name('assessment');
                    Route::post('/{id}/assessment-store/{coacheeId}', [CoachApiController::class, 'assessmentStore'])->name('assessment-store');
                    Route::post('/{id}/assessment-submit/{coacheeId}', [CoachApiController::class, 'assessmentSubmit'])->name('assessment-submit');
                    Route::post('/update-session', [CoachApiController::class, 'changeSessionDate'])->name('update-session');
                    Route::put('/{id}/finish-coaching', [CoachApiController::class, 'finishCoaching'])->name('finish-coaching');
                });
            Route::get('/{id}/{module}/{type}', [CoacheeApiController::class, 'showDocument'])->name('show.document');
        });


    // Bantara Callback
    Route::post('/callback/course/{enrollment}', [CertificateApiController::class, 'bantaraCallback'])->name('bantara-callback');
    Route::post('/callback/mentoring/{mentoring}', [MentorApiController::class, 'bantaraCallback'])->name('mentoring-callback');
});
