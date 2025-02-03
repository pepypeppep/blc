<?php

use Illuminate\Support\Facades\Route;
use Modules\PendidikanLanjutan\app\Http\Controllers\PendidikanLanjutanController;
use Modules\PendidikanLanjutan\app\Http\Controllers\VacancyController;
use Modules\PendidikanLanjutan\app\Http\Controllers\VacancyParticipantController;

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
        Route::get('/', [VacancyController::class, 'index'])->name('vacancies.index');
        Route::get('create', [VacancyController::class, 'create'])->name('vacancies.create');
        Route::post('/', [VacancyController::class, 'store'])->name('vacancies.store');
        Route::get('{id}', [VacancyController::class, 'show'])->name('vacancies.show');
        Route::get('{id}/edit', [VacancyController::class, 'edit'])->name('vacancies.edit');
        Route::put('{id}', [VacancyController::class, 'update'])->name('vacancies.update');
        Route::delete('{id}', [VacancyController::class, 'destroy'])->name('vacancies.destroy');
        Route::post('/import', [VacancyController::class, 'import'])->name('vacancies.import');
        Route::put('{id}/update-status', [VacancyController::class, 'updatePublicationStatus'])->name('vacancies.update-status');
    });

    Route::prefix('vacancies-participant')->group(function () {
        Route::put('/update-status/{vacancyId}/{userId}', [VacancyParticipantController::class, 'updateStatus'])->name('vacancies-participant.update.status');
        Route::post('/upload-file/{vacancyId}/{userId}', [VacancyParticipantController::class, 'uploadFile'])->name('vacancies-participant.upload.file');
    });
});

Route::post('/get-file/{vacancyAttachmentId}/{userId}', [VacancyParticipantController::class, 'getFile'])->name('vacancies-participant.get.file');
