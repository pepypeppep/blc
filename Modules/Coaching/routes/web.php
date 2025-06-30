<?php

use Illuminate\Support\Facades\Route;
use Modules\Coaching\app\Http\Controllers\CoacheeController;
use Modules\Coaching\app\Http\Controllers\CoachingController;
use Modules\Coaching\app\Http\Controllers\CoachController;

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
        Route::get('/', [CoachController::class, 'index'])->name('index');
        Route::get('/create', [CoachController::class, 'create'])->name('create');
        Route::post('/', [CoachController::class, 'store'])->name('store');
        Route::get('/{coachingId}', [CoachController::class, 'show'])->name('show');
        Route::put('/{coachingId}/set-consensus', [CoachController::class, 'initiateConsensus'])->name('set-consensus');
        Route::put('/{coachingId}/proses-coaching', [CoachController::class, 'processCoaching'])->name('process-coaching');
        Route::get('{coachingId}/penilaian/{coacheeId}', [CoachController::class, 'assessment'])->name('penilaian');
        Route::post('{coachingId}/penilaian/{coacheeId}', [CoachController::class, 'assessmentStore'])->name('penilaian.store');
        Route::post('{coachingId}/kirim-penilaian/{coacheeId}', [CoachController::class, 'assessmentSubmit'])->name('penilaian.kirim');
        Route::get('/{detailId}/img', [CoachController::class, 'viewImage'])->name('view.img');
        Route::get('/{id}/document', [CoachController::class, 'showDocumentSpt'])->name('view.spt');
        Route::get('/{id}/report', [CoachController::class, 'showReport'])->name('view.report');
        Route::post('/review', [CoachController::class, 'reviewStore'])->name('review');
    });
});
