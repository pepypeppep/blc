<?php

use Illuminate\Support\Facades\Route;
use Modules\Coaching\app\Http\Controllers\CoacheeController;
use Modules\Coaching\app\Http\Controllers\CoachingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'student', 'as' => 'student.'], function () {
    Route::prefix('/coachee')->name('coachee.')->group(function () {
        Route::get('/', [CoacheeController::class, 'index'])->name('index');
        Route::get('/{id}/show', [CoacheeController::class, 'show'])->name('show');
        Route::get('/{id}/document', [CoacheeController::class, 'show'])->name('view.document');
        Route::post('/{id}/report', [CoacheeController::class, 'report'])->name('report');
        Route::post('/{id}/update', [CoacheeController::class, 'updateSession'])->name('update.session');
        Route::post('/{id}/approve', [CoacheeController::class, 'submitForApproval'])->name('approve');
        Route::post('/{id}/reject', [CoacheeController::class, 'reject'])->name('reject');
    });

    Route::group(['prefix' => 'coach', 'as' => 'coach.'], function () {
        Route::get('/', function () {
            return 'Coach';
        })->name('index');
        // Route::get('/', [MentorController::class, 'index'])->name('index');
        // Route::get('{id}/show', [MentorController::class, 'show'])->name('show');
        // Route::post('{id}/approve', [MentorController::class, 'approve'])->name('approve');
        // Route::post('{id}/reject', [MentorController::class, 'reject'])->name('reject');
        // Route::post('{id}/review', [MentorController::class, 'review'])->name('review');
        // Route::get('{id}/evaluasi', [MentorController::class, 'evaluasi'])->name('evaluasi');
        // Route::post('{id}/evaluasi', [MentorController::class, 'evaluasiStore'])->name('evaluasi.store');
        // Route::post('{id}/kirim-evaluasi', [MentorController::class, 'kirimEvaluasi'])->name('evaluasi.kirim');
    });
});
