<?php

use Illuminate\Support\Facades\Route;
use Modules\PendidikanLanjutan\app\Http\Controllers\PendidikanLanjutanController;

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

Route::prefix('vacancies')->group(function () {
    Route::get('/', [VacancyController::class, 'index'])->name('vacancies.index');
    Route::get('create', [VacancyController::class, 'create'])->name('vacancies.create');
    Route::post('/', [VacancyController::class, 'store'])->name('vacancies.store');
    Route::get('{id}', [VacancyController::class, 'show'])->name('vacancies.show');
    Route::get('{id}/edit', [VacancyController::class, 'edit'])->name('vacancies.edit');
    Route::put('{id}', [VacancyController::class, 'update'])->name('vacancies.update');
    Route::delete('{id}', [VacancyController::class, 'destroy'])->name('vacancies.destroy');
});
