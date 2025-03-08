<?php

use Illuminate\Support\Facades\Route;
use Modules\Pegawai\app\Http\Controllers\PegawaiController;
use Modules\Pegawai\app\Http\Controllers\VacancyController;
use Modules\Pegawai\app\Http\Controllers\VacancyParticipantController;

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

// Route::middleware(['auth:admin', 'translation'])
//     ->name('admin.')
//     ->prefix('admin')
//     ->group(function () {
//         Route::resource('pegawai', PegawaiController::class)->names('pegawai');
//     });

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('pegawai')->group(function () {
        Route::prefix('pendidikanlanjutan')->group(function () {
            Route::get('/', [PegawaiController::class, 'indexPendidikanLanjutan'])->name('pegawai.pendidikanlanjutan.index');
            Route::get('{id}', [PegawaiController::class, 'showPendidikanLanjutan'])->name('pegawai.pendidikanlanjutan.show');
        });
    });
});
