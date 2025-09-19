<?php

use Illuminate\Support\Facades\Route;
use Modules\InstructorEvaluation\app\Http\Controllers\InstructorEvaluationController;

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


// As Admin Role
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::get('instructor-evaluation', [InstructorEvaluationController::class, 'index'])->name('instructorevaluation.index');
});

// As Student Role
Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'student', 'as' => 'student.'], function () {
    Route::get('instructor-evaluation/create/{course}/{instructor?}', [InstructorEvaluationController::class, 'create'])->name('instructorevaluation.create');
    Route::post('instructor-evaluation', [InstructorEvaluationController::class, 'store'])->name('instructorevaluation.store');
});
