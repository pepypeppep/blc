<?php

use Illuminate\Support\Facades\Route;
use Modules\Pengumuman\app\Http\Controllers\PengumumanController;

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('pengumuman')->as('pengumuman.')->group(function () {

        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::get('/create', [PengumumanController::class, 'create'])->name('create');
        Route::post('/store', [PengumumanController::class, 'store'])->name('store');
        Route::get('{id}/edit', [PengumumanController::class, 'edit'])->name('edit');
        Route::put('{id}/update', [PengumumanController::class, 'update'])->name('update');
    });
});