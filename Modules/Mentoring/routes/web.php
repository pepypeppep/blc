<?php

use Illuminate\Support\Facades\Route;
use Modules\Mentoring\app\Http\Controllers\MenteeController;
use Modules\Mentoring\app\Http\Controllers\MentorController;

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
    Route::prefix('/mentee')->name('mentee.')->group(function () {
        Route::get('/', [MenteeController::class, 'index'])->name('index');
        Route::get('/create', [MenteeController::class, 'create'])->name('create');
        Route::post('/', [MenteeController::class, 'store'])->name('store');
        Route::put('/session/update', [MenteeController::class, 'updateSession'])->name('update.session');
        Route::put('/{mentoring}/report', [MenteeController::class, 'updateFinalReport'])->name('report');
        Route::get('/{id}', [MenteeController::class, 'show'])->name('show');
        Route::get('/{id}/document/{type}', [MenteeController::class, 'showDocument'])->name('view.document');
        Route::get('/{id}/img', [MenteeController::class, 'viewImage'])->name('view.img');
        Route::put('/{id}/submit', [MenteeController::class, 'submitForApproval'])->name('submit');
    });
    Route::get('/mentor', [MentorController::class, 'index'])->name('mentor.index');
});
