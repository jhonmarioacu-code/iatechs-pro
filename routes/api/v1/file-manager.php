<?php

declare(strict_types=1);

use App\Domains\FileManager\Controllers\FileManagerController;
use Illuminate\Support\Facades\Route;

Route::prefix('file-manager')
    ->name('file-manager.')
    ->group(function () {
        Route::get('/', [FileManagerController::class, 'index'])
            ->middleware('permission:file-manager.view')
            ->name('index');

        Route::post('/', [FileManagerController::class, 'store'])
            ->middleware('permission:file-manager.create')
            ->name('store');

        Route::get('/{fileManager}', [FileManagerController::class, 'show'])
            ->middleware('permission:file-manager.view')
            ->name('show');

        Route::put('/{fileManager}', [FileManagerController::class, 'update'])
            ->middleware('permission:file-manager.update')
            ->name('update');

        Route::delete('/{fileManager}', [FileManagerController::class, 'destroy'])
            ->middleware('permission:file-manager.delete')
            ->name('destroy');
    });
