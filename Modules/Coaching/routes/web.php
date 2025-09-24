<?php

use Illuminate\Support\Facades\Route;
use Modules\Coaching\app\Http\Controllers\CoacheeController;
use Modules\Coaching\app\Http\Controllers\CoachingController;
use Modules\Coaching\app\Http\Controllers\CoachController;
use Modules\Coaching\app\Http\Controllers\CoachingSignerController;

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
        Route::post('/{id}/tolak-konsensus', [CoacheeController::class, 'tolakKonsensus'])->name('tolak-konsensus');
        Route::post('/{id}/join-konsensus', [CoacheeController::class, 'joinKonsensus'])->name('join-konsensus');
        Route::post('/submit-report', [CoacheeController::class, 'submitReport'])->name('submit-report');
        Route::put('/submit-final-report/{coachingId}', [CoacheeController::class, 'submitFinalReport'])->name('submit-final-report');
        Route::get('/preview/{coachingId}/{coachingSessionId}', [CoacheeController::class, 'previewFileName'])->name('preview');
        Route::get('/preview-final-report/{coachingId}/{coachingUserId}', [CoacheeController::class, 'previewFinalReport'])->name('preview-final-report');
    });

    Route::group(['prefix' => 'coach', 'as' => 'coach.'], function () {
        Route::get('/', [CoachController::class, 'index'])->name('index');
        Route::get('/create', [CoachController::class, 'create'])->name('create');
        Route::post('/', [CoachController::class, 'store'])->name('store');
        Route::get('/{coachingId}', [CoachController::class, 'show'])->name('show');
        Route::get('/{coachingId}/edit', [CoachController::class, 'edit'])->name('edit');
        Route::put('/{coachingId}/update', [CoachController::class, 'update'])->name('update');
        Route::put('/{coachingId}/set-consensus', [CoachController::class, 'initiateConsensus'])->name('set-consensus');
        Route::put('/{coachingId}/proses-coaching', [CoachController::class, 'processCoaching'])->name('process-coaching');
        Route::put('/{coachingId}/send-assessment', [CoachController::class, 'finishCoaching'])->name('send-assessment');
        Route::get('{coachingId}/penilaian/{coacheeId}', [CoachController::class, 'assessment'])->name('penilaian');
        Route::post('{coachingId}/penilaian/{coacheeId}', [CoachController::class, 'assessmentStore'])->name('penilaian.store');
        Route::post('{coachingId}/kirim-penilaian/{coacheeId}', [CoachController::class, 'assessmentSubmit'])->name('penilaian.kirim');
        Route::put('/ubah-pertemuan', [CoachController::class, 'changeSessionDate'])->name('change-session');
        Route::get('/{detailId}/img', [CoachController::class, 'viewImage'])->name('view.img');
        Route::get('/{id}/document', [CoachController::class, 'showDocumentSpt'])->name('view.spt');
        Route::get('/{id}/report', [CoachController::class, 'showReport'])->name('view.report');
        Route::put('/review', [CoachController::class, 'reviewStore'])->name('review');
    });
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::group(['prefix' => 'coaching', 'as' => 'coaching.'], function () {
        Route::get('/', [CoachingController::class, 'index'])->name('index');
        Route::get('{id}/show', [CoachingController::class, 'show'])->name('show');

        // Route::get('{coachingId}/penilaian/{coacheeId}', [CoachingController::class, 'assessment'])->name('penilaian');
        Route::put('{id}/update-certificate', [CoachingController::class, 'updateCertificate'])->name('update-certificate');
        Route::get('get-users', [CoachingController::class, 'getUsers'])->name('get-users');
        Route::post('{id}/request-sign-certificate', [CoachingController::class, 'requestSignCertificate'])->name('request-sign-certificate');
        Route::get('public-certificate/{uuid}', [CoachingController::class, 'publicCertificate'])->name('public-certificate');
        Route::get('/{id}/img', [CoachingController::class, 'viewImage'])->name('view.img');
        Route::get('/{id}/document', [CoachingController::class, 'showDocumentSpt'])->name('view.spt');
        Route::get('/{id}/report', [CoachingController::class, 'showReport'])->name('view.report');
        Route::get('/{id}/document/{type}', [CoachingController::class, 'showDocument'])->name('view.document');


        // Certificate
        Route::group(['prefix' => 'certificate', 'as' => 'certificate.'], function () {
            Route::get('list-signer', [CoachingSignerController::class, 'list'])->name('list-signer');
            Route::post('store-signer', [CoachingSignerController::class, 'storeSigners'])->name('store-signer');
            Route::post('store-type', [CoachingSignerController::class, 'storeType'])->name('store-type');
        });
    });
});
