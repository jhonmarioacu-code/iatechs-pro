<?php

declare(strict_types=1);

use App\Domains\Assets\Controllers\AssetController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
    ->prefix('assets')
    ->name('assets.')
    ->group(function () {
        Route::get('/', [AssetController::class, 'index'])->middleware('permission:assets.view')->name('index');
        Route::post('/', [AssetController::class, 'store'])->middleware('permission:assets.create')->name('store');
        Route::get('/{asset}', [AssetController::class, 'show'])->middleware('permission:assets.view')->name('show');
        Route::put('/{asset}', [AssetController::class, 'update'])->middleware('permission:assets.update')->name('update');
        Route::delete('/{asset}', [AssetController::class, 'destroy'])->middleware('permission:assets.delete')->name('destroy');
    });

