<?php

use Illuminate\Support\Facades\Route;
use Modules\PendidikanLanjutan\app\Http\Controllers\VacancyController;
use Modules\PendidikanLanjutan\app\Http\Controllers\VacancyScheduleController;
use Modules\PendidikanLanjutan\app\Http\Controllers\PendidikanLanjutanController;
use Modules\PendidikanLanjutan\app\Http\Controllers\VacancyParticipantController;
use Modules\PendidikanLanjutan\app\Http\Controllers\MasterPendidikanLanjutanController;

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

Route::middleware(['auth:admin', 'translation'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::resource('pendidikanlanjutan', PendidikanLanjutanController::class)->names('pendidikanlanjutan');
    });

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('vacancies')->group(function () {
        Route::get('/sidebar-counter', [VacancyController::class, 'sidebarCounter'])->name('vacancies.sidebar.counter');

        Route::prefix('master')->group(function () {
            Route::get('/report-file', [MasterPendidikanLanjutanController::class, 'reportFileIndex'])->name('vacancies.master.file.index');
            Route::post('/report-file', [MasterPendidikanLanjutanController::class, 'reportFileStore'])->name('vacancies.master.file.store');
            Route::get('/report-file/{id}', [MasterPendidikanLanjutanController::class, 'reportFileEdit'])->name('vacancies.master.file.edit');
            Route::put('/report-file/{id}', [MasterPendidikanLanjutanController::class, 'reportFileUpdate'])->name('vacancies.master.file.update');

            Route::get('/schedule', [VacancyScheduleController::class, 'index'])->name('vacancies.master.schedule.index');
            Route::post('/schedule', [VacancyScheduleController::class, 'store'])->name('vacancies.master.schedule.store');
            Route::put('/schedule/{id}', [VacancyScheduleController::class, 'update'])->name('vacancies.master.schedule.update');
        });

        Route::prefix('peserta')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'indexPeserta'])->name('vacancies.peserta.index');
        });

        Route::prefix('verification')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'indexVerif'])->name('vacancies.verification.index');
            Route::get('{id}', [PendidikanLanjutanController::class, 'showVerif'])->name('vacancies.verification.show');
        });

        Route::prefix('assessment')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'indexAssesment'])->name('vacancies.assessment.index');
            Route::get('{id}', [PendidikanLanjutanController::class, 'showAssesment'])->name('vacancies.assessment.show');
        });

        Route::prefix('sk')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'indexSK'])->name('vacancies.sk.index');
            Route::get('{id}', [PendidikanLanjutanController::class, 'showSK'])->name('vacancies.sk.show');
        });

        Route::prefix('report')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'indexReport'])->name('vacancies.report.index');
            Route::get('{id}', [PendidikanLanjutanController::class, 'showReport'])->name('vacancies.report.show');
        });

        Route::prefix('extend')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'indexExtend'])->name('vacancies.extend.index');
            Route::get('{id}', [PendidikanLanjutanController::class, 'showExtend'])->name('vacancies.extend.show');
        });

        Route::prefix('activation')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'indexActivation'])->name('vacancies.activation.index');
            Route::get('{id}', [PendidikanLanjutanController::class, 'showActivation'])->name('vacancies.activation.show');
        });

        Route::prefix('done')->group(function () {
            Route::get('/', [PendidikanLanjutanController::class, 'indexDone'])->name('vacancies.done.index');
            Route::get('{id}', [PendidikanLanjutanController::class, 'showDone'])->name('vacancies.done.show');
        });

        Route::get('/', [VacancyController::class, 'index'])->name('vacancies.index');
        Route::get('create', [VacancyController::class, 'create'])->name('vacancies.create');
        Route::post('/', [VacancyController::class, 'store'])->name('vacancies.store');
        Route::get('{id}', [VacancyController::class, 'show'])->name('vacancies.show');
        Route::get('{id}/edit', [VacancyController::class, 'edit'])->name('vacancies.edit');
        Route::put('{id}', [VacancyController::class, 'update'])->name('vacancies.update');
        Route::delete('{id}', [VacancyController::class, 'destroy'])->name('vacancies.destroy');
        Route::post('/import', [VacancyController::class, 'import'])->name('vacancies.import');
        Route::put('{id}/update-status', [VacancyController::class, 'updatePublicationStatus'])->name('vacancies.update-status');
        Route::post('{id}/update-attachment', [VacancyController::class, 'updateAttachments'])->name('vacancies.update-attachment');
    });

    Route::prefix('vacancies-participant')->group(function () {
        Route::put('/update-status/{vacancyUserId}', [VacancyParticipantController::class, 'updateStatus'])->name('vacancies-participant.update.status');
        Route::post('/upload-file/{vacancyId}/{vacancyUserId}', [VacancyParticipantController::class, 'uploadFile'])->name('vacancies-participant.upload.file');
        Route::put('/update-report-status/{vacancyReportId}', [VacancyParticipantController::class, 'updateReportStatus'])->name('vacancies-participant.update.report.status');
        Route::put('/update-activation-status/{id}', [VacancyParticipantController::class, 'updateActivationStatus'])->name('vacancies-participant.update.activation.status');
    });
});

Route::get('/get-file/{vacancyAttachmentId}/{userId}', [VacancyParticipantController::class, 'getFile'])->name('vacancies-participant.get.file');
Route::get('/get-draft-file/{vacancyAttachmentId}/{userId}', [VacancyParticipantController::class, 'getDraftFile'])->name('vacancies-participant.get.draft.file');
Route::get('/get-activation-file/{id}', [VacancyParticipantController::class, 'getActivationFile'])->name('vacancies-participant.get.activation.file');
Route::get('/get-file/{vacancyReport}', [VacancyParticipantController::class, 'getReportFile'])->name('vacancies-participant.get.report.file');
