<?php

use Illuminate\Support\Facades\Route;
use Modules\Article\app\Http\Controllers\ArticleCategoryController;
use Modules\Article\app\Http\Controllers\ArticleCommentController;
use Modules\Article\app\Http\Controllers\ArticleController;

Route::middleware(['auth:admin', 'translation'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::resource('articles', ArticleController::class)->names('articles');
        Route::put('/articles/status-update/{id}', [ArticleController::class, 'statusUpdate'])->name('articles.status-update');

        Route::resource('article-category', ArticleCategoryController::class)->names('article-category')->except('show');
        Route::put('/article-category/status-update/{id}', [ArticleCategoryController::class, 'statusUpdate'])->name('article-category.status-update');

        Route::resource('article-comment', ArticleCommentController::class)->names('article-comment')->only(['index', 'show', 'destroy']);
        Route::put('/article-comment/status-update/{id}', [ArticleCommentController::class, 'statusUpdate'])->name('article-comment.status-update');
    });
