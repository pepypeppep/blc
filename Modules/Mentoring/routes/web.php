<?php

use Illuminate\Support\Facades\Route;
use Modules\Mentoring\app\Http\Controllers\MentoringController;

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
    Route::get('/mentee', function () {
        return 'Page Mentee';
    })->name('mentee.index');
    Route::get('/mentor', function () {
        return 'Page Mentor';
    })->name('mentor.index');
});
