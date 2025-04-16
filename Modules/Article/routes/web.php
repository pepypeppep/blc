<?php

use Illuminate\Support\Facades\Route;
use Modules\Article\app\Http\Controllers\ArticleController;
// use Modules\Article\app\Http\Controllers\ArticleCategoryController;
// use Modules\Article\app\Http\Controllers\ArticleCommentController;

// Route::middleware(['auth:admin', 'translation'])
//     ->name('admin.')
//     ->prefix('admin')
//     ->group(function () {
//         Route::resource('articles', ArticleController::class)->names('articles');
//         Route::put('/articles/status-update/{id}', [ArticleController::class, 'statusUpdate'])->name('articles.status-update');

//         Route::resource('article-category', ArticleCategoryController::class)->names('article-category')->except('show');
//         Route::put('/article-category/status-update/{id}', [ArticleCategoryController::class, 'statusUpdate'])->name('article-category.status-update');

//         Route::resource('article-comment', ArticleCommentController::class)->names('article-comment')->only(['index', 'show', 'destroy']);
//         Route::put('/article-comment/status-update/{id}', [ArticleCommentController::class, 'statusUpdate'])->name('article-comment.status-update');
//     });

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('knowledges')->as('knowledge.')->group(function () {

        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('{id}', [ArticleController::class, 'show'])->name('detail');
        Route::put('{id}/update-status', [ArticleController::class, 'updateStatus'])->name('update-status');
    });
});