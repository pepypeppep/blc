<?php

use Illuminate\Support\Facades\Route;
use Modules\Mentoring\app\Http\Controllers\MenteeController;
use Modules\Mentoring\app\Http\Controllers\MentorController;

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
    Route::prefix('/mentee')->name('mentee.')->group(function () {
        Route::get('/', [MenteeController::class, 'index'])->name('index');
        Route::get('/create', [MenteeController::class, 'create'])->name('create');
        Route::post('/', [MenteeController::class, 'store'])->name('store');
        Route::put('/session/update', [MenteeController::class, 'updateSession'])->name('update.session');
        Route::put('/{mentoring}/report', [MenteeController::class, 'updateFinalReport'])->name('report');
        Route::get('/{id}', [MenteeController::class, 'show'])->name('show');
        Route::get('/{id}/document/{type}', [MenteeController::class, 'showDocument'])->name('view.document');
        Route::get('/{id}/img', [MenteeController::class, 'viewImage'])->name('view.img');
        Route::put('/{id}/submit', [MenteeController::class, 'submitForApproval'])->name('submit');
    });

    Route::group(['prefix' => 'mentor', 'as' => 'mentor.'], function () {
        Route::get('/', [MentorController::class, 'index'])->name('index');
        Route::get('{id}/show', [MentorController::class, 'show'])->name('show');
        Route::post('{id}/approve', [MentorController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [MentorController::class, 'reject'])->name('reject');
        Route::post('{id}/review', [MentorController::class, 'review'])->name('review');
        Route::get('{id}/evaluasi', [MentorController::class, 'evaluasi'])->name('evaluasi');
        Route::post('{id}/evaluasi', [MentorController::class, 'evaluasiStore'])->name('evaluasi.store');
        Route::post('{id}/kirim-evaluasi', [MentorController::class, 'kirimEvaluasi'])->name('evaluasi.kirim');
    });
});
