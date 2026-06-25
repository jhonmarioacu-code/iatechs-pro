<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\KnowledgeBase\Controllers\KnowledgeBaseController;

Route::middleware([
    'auth'
])
->prefix('knowledge-base')
->group(function () {

    Route::get(
        '/',
        [
            KnowledgeBaseController::class,
            'index'
        ]
    );

    Route::post(
        '/',
        [
            KnowledgeBaseController::class,
            'store'
        ]
    );

    Route::get(
        '/{knowledgeArticle}',
        [
            KnowledgeBaseController::class,
            'show'
        ]
    );

    Route::put(
        '/{knowledgeArticle}',
        [
            KnowledgeBaseController::class,
            'update'
        ]
    );

    Route::delete(
        '/{knowledgeArticle}',
        [
            KnowledgeBaseController::class,
            'destroy'
        ]
    );

    Route::post(
        '/{knowledgeArticle}/publish',
        [
            KnowledgeBaseController::class,
            'publish'
        ]
    );

    Route::post(
        '/{knowledgeArticle}/archive',
        [
            KnowledgeBaseController::class,
            'archive'
        ]
    );
});