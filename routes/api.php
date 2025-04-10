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

Route::middleware('auth:sso-api')->get('/hello', function (Request $request) {

    return [
        'message' => sprintf("Hello my username is %s", $request->user()->username)
    ];
});

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
        });
    });

    // Article
    Route::name('article.')->group(function () {
        Route::get('/articles', [ArticleController::class, 'index'])->name('index');
    });
});
