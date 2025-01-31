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
        Route::put('{id}/update-status', [VacancyController::class, 'updatePublicationStatus'])->name('vacancies.update-status');
    });
});

Route::prefix('vacancies-participant')->group(function () {
    Route::get('/', [VacancyParticipantController::class, 'index'])->name('vacancies-participant.index');
    Route::post('{id}/register', [VacancyParticipantController::class, 'register'])->name('vacancies-participant.register');
    Route::get('{id}', [VacancyParticipantController::class, 'show'])->name('vacancies-participant.show');
    Route::post('{$vacancyDetailId}/upload-file/{$vacancyUserId}', [VacancyParticipantController::class, 'uploadFile'])->name('vacancies-participant.upload-file');
});
