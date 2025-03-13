<?php

use App\Http\Controllers\Api\CertificateApiController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ReviewApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/courses', [CourseApiController::class, 'courses'])->name('courses');
    Route::get('/courses/{slug}', [CourseApiController::class, 'showCourse'])->name('courses.show');
    Route::get('/courses/{slug}/learning', [CourseApiController::class, 'learningCourse'])->name('courses.learning');
    Route::get('/courses/{courseId}/get-thumbnail', [CourseApiController::class, 'getCourseThumbnail'])->name('courses.get-thumbnail');
    Route::get('/courses-categories', [CourseApiController::class, 'categories'])->name('courses.categories');
    Route::get('/courses-levels', [CourseApiController::class, 'levels'])->name('courses.levels');

    Route::get('/certificates', [CertificateApiController::class, 'getCertificatesForStudent'])->name('certificates');
        
    Route::get('/reviews', [ReviewApiController::class, 'reviews'])->name('reviews');
});
