<?php

declare(strict_types=1);

use App\Domains\HumanResources\Controllers\HumanResourceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('human-resources')
    ->name('human-resources.')
    ->group(function () {
        Route::get('/', [HumanResourceController::class, 'index'])->name('index');
        Route::post('/', [HumanResourceController::class, 'store'])->name('store');
        Route::get('/{humanResource}', [HumanResourceController::class, 'show'])->name('show');
        Route::put('/{humanResource}', [HumanResourceController::class, 'update'])->name('update');
        Route::delete('/{humanResource}', [HumanResourceController::class, 'destroy'])->name('destroy');
    });
