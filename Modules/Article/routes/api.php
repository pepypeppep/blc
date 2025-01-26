<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Article\app\Http\Controllers\ArticleController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//     Route::get('article', fn (Request $request) => $request->user())->name('article');
// });

// Route::name('api.')->group(function () {
//     Route::resource('article', ArticleController::class)->names('article');
// });

Route::prefix('v1')->name('api.')->group(function () {
    Route::resource('article', ArticleController::class)->names('article');
    Route::put('article/{slug}', [ArticleController::class, 'update'])->name('article.update');
    Route::delete('article/{slug}', [ArticleController::class, 'destroy'])->name('article.destroy');
});
