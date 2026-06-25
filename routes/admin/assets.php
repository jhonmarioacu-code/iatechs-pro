<?php

declare(strict_types=1);

use App\Domains\Assets\Controllers\AssetController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('assets')
    ->name('assets.')
    ->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::post('/', [AssetController::class, 'store'])->name('store');
        Route::get('/{asset}', [AssetController::class, 'show'])->name('show');
        Route::put('/{asset}', [AssetController::class, 'update'])->name('update');
        Route::delete('/{asset}', [AssetController::class, 'destroy'])->name('destroy');
    });
