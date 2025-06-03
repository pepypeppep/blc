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
    Route::get('/mentee', [MenteeController::class, 'index'])->name('mentee.index');
    Route::get('/mentor', [MentorController::class, 'index'])->name('mentor.index');
});
