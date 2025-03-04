<?php

use Illuminate\Support\Facades\Route;
use Modules\CertificateBuilder\app\Http\Controllers\CertificateBuilderController;

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

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::post('certificate-builder/item/update', [CertificateBuilderController::class, 'updateItem'])->name('certificate-builder.item.update');
    Route::resource('certificate-builder', CertificateBuilderController::class)->names('certificate-builder');
    Route::get('certificate-builder/{id}/getBg', [CertificateBuilderController::class, 'getBg'])->name('certificate-builder.getBg');
    Route::get('certificate-builder/{id}/getSg', [CertificateBuilderController::class, 'getSg'])->name('certificate-builder.getSg');
    Route::get('certificate-builder/{id}/getBg2', [CertificateBuilderController::class, 'getBg2'])->name('certificate-builder.getBg2');
    Route::get('certificate-builder/{id}/getSg2', [CertificateBuilderController::class, 'getSg2'])->name('certificate-builder.getSg2');
});
