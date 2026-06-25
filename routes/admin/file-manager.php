<?php

declare(strict_types=1);

use App\Domains\FileManager\Controllers\FileManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('file-manager')
    ->name('file-manager.')
    ->group(function () {
        Route::get('/', [FileManagerController::class, 'index'])->name('index');
        Route::post('/', [FileManagerController::class, 'store'])->name('store');
        Route::get('/{fileManager}', [FileManagerController::class, 'show'])->name('show');
        Route::put('/{fileManager}', [FileManagerController::class, 'update'])->name('update');
        Route::delete('/{fileManager}', [FileManagerController::class, 'destroy'])->name('destroy');
    });
