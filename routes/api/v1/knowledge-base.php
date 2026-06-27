<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\KnowledgeBase\Controllers\KnowledgeBaseController;

Route::prefix('knowledge-base')
    ->name('knowledge-base.')
    ->group(function () {
        Route::get('/', [KnowledgeBaseController::class, 'index'])
            ->middleware('permission:knowledge.view')
            ->name('index');

        Route::post('/', [KnowledgeBaseController::class, 'store'])
            ->middleware('permission:knowledge.create')
            ->name('store');

        Route::get('/{knowledgeArticle}', [KnowledgeBaseController::class, 'show'])
            ->middleware('permission:knowledge.view')
            ->name('show');

        Route::put('/{knowledgeArticle}', [KnowledgeBaseController::class, 'update'])
            ->middleware('permission:knowledge.update')
            ->name('update');

        Route::delete('/{knowledgeArticle}', [KnowledgeBaseController::class, 'destroy'])
            ->middleware('permission:knowledge.delete')
            ->name('destroy');

        Route::post('/{knowledgeArticle}/publish', [KnowledgeBaseController::class, 'publish'])
            ->middleware('permission:knowledge.update')
            ->name('publish');

        Route::post('/{knowledgeArticle}/archive', [KnowledgeBaseController::class, 'archive'])
            ->middleware('permission:knowledge.update')
            ->name('archive');
    });

