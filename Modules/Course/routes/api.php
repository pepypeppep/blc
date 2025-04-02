<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Course\app\Http\Controllers\Api\CourseController;
use Modules\Course\app\Http\Controllers\Api\CourseReviewController;
use Modules\Course\app\Http\Controllers\Api\CourseCategoryController;
use Modules\Course\app\Http\Controllers\Api\CourseLevelController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('course', fn(Request $request) => $request->user())->name('course');
});


Route::name('api.')->group(function () {
    Route::get('course/{slug}/file', [CourseController::class, 'file'])->name('course.file');
    Route::resource('course', CourseController::class)->names('course');
    Route::resource('course-reviews', CourseReviewController::class)->names('course.reviews');
    Route::get('course-category/{id}/thumbnail', 'Modules\Course\app\Http\Controllers\CourseCategoryController@getFile')->name('course-category.thumbnail');
    Route::resource('course-category', CourseCategoryController::class)->names('course.category');
    Route::resource('course-level', CourseLevelController::class)->names('course.level');
});
