<?php

declare(strict_types=1);

use App\Domains\BusinessIntelligence\Controllers\BusinessMetricController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('business-intelligence')
    ->name('business-intelligence.')
    ->group(function () {
        Route::get('/', [BusinessMetricController::class, 'index'])->name('index');
        Route::post('/', [BusinessMetricController::class, 'store'])->name('store');
        Route::get('/{businessMetric}', [BusinessMetricController::class, 'show'])->name('show');
        Route::put('/{businessMetric}', [BusinessMetricController::class, 'update'])->name('update');
        Route::delete('/{businessMetric}', [BusinessMetricController::class, 'destroy'])->name('destroy');
    });
