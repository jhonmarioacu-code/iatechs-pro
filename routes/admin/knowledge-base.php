<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\KnowledgeBase\Controllers\KnowledgeBaseController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
->prefix('knowledge-base')
->group(function () {

    Route::get(
        '/',
        [
            KnowledgeBaseController::class,
            'index'
        ]
    )->middleware('permission:knowledge.view');

    Route::post(
        '/',
        [
            KnowledgeBaseController::class,
            'store'
        ]
    )->middleware('permission:knowledge.create');

    Route::get(
        '/{knowledgeArticle}',
        [
            KnowledgeBaseController::class,
            'show'
        ]
    )->middleware('permission:knowledge.view');

    Route::put(
        '/{knowledgeArticle}',
        [
            KnowledgeBaseController::class,
            'update'
        ]
    )->middleware('permission:knowledge.update');

    Route::delete(
        '/{knowledgeArticle}',
        [
            KnowledgeBaseController::class,
            'destroy'
        ]
    )->middleware('permission:knowledge.delete');

    Route::post(
        '/{knowledgeArticle}/publish',
        [
            KnowledgeBaseController::class,
            'publish'
        ]
    )->middleware('permission:knowledge.update');

    Route::post(
        '/{knowledgeArticle}/archive',
        [
            KnowledgeBaseController::class,
            'archive'
        ]
    )->middleware('permission:knowledge.update');
});
