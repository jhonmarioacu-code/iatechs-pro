<?php

declare(strict_types=1);

use App\Domains\Analytics\Controllers\AnalyticController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('analytics')
    ->name('analytics.')
    ->group(function () {
        Route::get('/', [AnalyticController::class, 'index'])->name('index');
        Route::post('/', [AnalyticController::class, 'store'])->name('store');
        Route::get('/{analytic}', [AnalyticController::class, 'show'])->name('show');
        Route::put('/{analytic}', [AnalyticController::class, 'update'])->name('update');
        Route::delete('/{analytic}', [AnalyticController::class, 'destroy'])->name('destroy');
    });
