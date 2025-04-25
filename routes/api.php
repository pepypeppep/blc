<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CertificateApiController;
use App\Http\Controllers\Api\PendidikanLanjutanController;
use App\Http\Controllers\Api\StudentLearningApiController;
use App\Http\Controllers\Auth\SSOController;

Route::middleware('auth:sso-api')->get('/hello', function (Request $request) {
    return [
        'message' => sprintf("Hello my username is %s", $request->user()->username)
    ];
});

Route::middleware('auth:sso-api')->get('/whoami', [SSOController::class, 'whoami'])->name('whoami');

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
            Route::get('/{id}', [PendidikanLanjutanController::class, 'show']);
            Route::get('/{id}/logs', [PendidikanLanjutanController::class, 'logs']);
        });
    });

    // Article
    Route::name('article.')->group(function () {
        Route::get('/articles', [ArticleController::class, 'index'])->name('index');
        Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('show');
        Route::get('/articles-popular', [ArticleController::class, 'popularArticles'])->name('popular');
        Route::get('/articles-tags', [ArticleController::class, 'articleTags'])->name('tags');
        Route::get('/articles-reviews/{id}', [ArticleController::class, 'articleReviews'])->name('reviews');
        Route::group(['middleware' => ['auth:sso-api']], function () {
            Route::post('/articles-reviews/{id}', [ArticleController::class, 'storeReviews'])->name('reviews.store');
        });
    });

    //student learning
    Route::prefix('student-learning')
        ->name('student-learning.')
        // ->middleware('auth:sso-api')
        ->group(function () {
            // GET: /api/student-learning/{slug}
            Route::get('/{slug}', [StudentLearningApiController::class, 'index'])->name('index');
            // POST: /api/student-learning/post-progresslesson
            Route::post('/post-progresslesson', [StudentLearningApiController::class, 'postProgresslesson'])->name('post-progresslesson');
            // POST: /api/student-learning/make-lesson-complete
            Route::post('/make-lesson-complete', [StudentLearningApiController::class, 'makeLessonComplete'])->name('make-lesson-complete');
            // GET: /api/student-learning/course/{courseId}/quiz/{quizId
            Route::get('/{courseId}/quiz/{quizId}', [StudentLearningApiController::class, 'quizIndex'])->name('quiz.index');
            // POST: /api/student-learning/quizzes/{id}
            Route::post('/quizzes/{id}', [StudentLearningApiController::class, 'quizStore'])->name('quiz.store');
            // GET: /api/student-learning/quiz/{id}/result/{resultId}
            Route::get('/quiz/{id}/result/{resultId}', [StudentLearningApiController::class, 'quizResult'])->name('quiz.result');
            // GET: /api/student-learning/rtl/{id}
            Route::get('/rtl/{id}', [StudentLearningApiController::class, 'rtlIndex'])->name('rtl.index');
            // POST: /api/student-learning/rtl/{id}
            Route::post('/rtl/{id}', [StudentLearningApiController::class, 'rtlStore'])->name('rtl.store');
        });

    // Bantara Callback
    Route::post('/bantara-callback/{enrollment}', [CertificateApiController::class, 'bantaraCallback'])->name('bantara-callback');
});