<?php

use Illuminate\Support\Facades\Route;
use Modules\Pengetahuan\app\Http\Controllers\VacancyController;
use Modules\Pengetahuan\app\Http\Controllers\VacancyScheduleController;
use Modules\Pengetahuan\app\Http\Controllers\PengetahuanController;
use Modules\Pengetahuan\app\Http\Controllers\VacancyParticipantController;
use Modules\Pengetahuan\app\Http\Controllers\MasterPengetahuanController;

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
        Route::resource('Pengetahuan', PengetahuanController::class)->names('Pengetahuan');
    });

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('pengetahuan')->group(function () {
        Route::resource('master-pengetahuan', PengetahuanController::class)->names('master-pengetahuan');
    });
});