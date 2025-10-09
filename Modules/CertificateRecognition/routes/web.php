<?php

use Illuminate\Support\Facades\Route;
use Modules\CertificateRecognition\app\Http\Controllers\CertificateRecognitionController;
use Modules\CertificateRecognition\app\Http\Controllers\PersonalCertificateRecognitionController;

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
    Route::get('certificate-recognition', [PersonalCertificateRecognitionController::class, 'index'])->name('certificate-recognition.index');
    Route::get('certificate-recognition/create', [PersonalCertificateRecognitionController::class, 'create'])->name('certificate-recognition.create');
    Route::post('certificate-recognition/store', [PersonalCertificateRecognitionController::class, 'store'])->name('certificate-recognition.store');
    Route::get('certificate-recognition/{id}/verify', [PersonalCertificateRecognitionController::class, 'verify'])->name('certificate-recognition.verify');
    Route::put('certificate-recognition/{id}/verify', [PersonalCertificateRecognitionController::class, 'verifyUpdate'])->name('certificate-recognition.verify.store');
    Route::get('certificate-recognition/{id}/edit', [PersonalCertificateRecognitionController::class, 'edit'])->name('certificate-recognition.edit');
    Route::delete('certificate-recognition/{id}', [PersonalCertificateRecognitionController::class, 'destroy'])->name('certificate-recognition.destroy');
    Route::get('certificate-recognition/{id}', [PersonalCertificateRecognitionController::class, 'show'])->name('certificate-recognition.show');
    // Route::get('certificate-recognition', [CertificateRecognitionController::class, 'index'])->name('certificate-recognition.index');
    // Route::get('certificate-recognition/create', [CertificateRecognitionController::class, 'create'])->name('certificate-recognition.create');
    // Route::post('certificate-recognition/store', [CertificateRecognitionController::class, 'store'])->name('certificate-recognition.store');
    // Route::get('certificate-recognition/{id}/verify', [CertificateRecognitionController::class, 'verify'])->name('certificate-recognition.verify');
    // Route::put('certificate-recognition/{id}/verify', [CertificateRecognitionController::class, 'verifyUpdate'])->name('certificate-recognition.verify.store');
    // Route::get('certificate-recognition/{id}/edit', [CertificateRecognitionController::class, 'edit'])->name('certificate-recognition.edit');
    // Route::delete('certificate-recognition/{id}', [CertificateRecognitionController::class, 'destroy'])->name('certificate-recognition.destroy');
    // Route::get('certificate-recognition/{id}', [CertificateRecognitionController::class, 'show'])->name('certificate-recognition.show');
});
