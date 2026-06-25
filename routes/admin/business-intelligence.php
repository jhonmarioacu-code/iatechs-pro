<?php

declare(strict_types=1);

use App\Domains\BusinessIntelligence\Controllers\BusinessMetricController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
    ->prefix('business-intelligence')
    ->name('business-intelligence.')
    ->group(function () {
        Route::get('/', [BusinessMetricController::class, 'index'])->middleware('permission:business-intelligence.view')->name('index');
        Route::post('/', [BusinessMetricController::class, 'store'])->middleware('permission:business-intelligence.create')->name('store');
        Route::get('/{businessMetric}', [BusinessMetricController::class, 'show'])->middleware('permission:business-intelligence.view')->name('show');
        Route::put('/{businessMetric}', [BusinessMetricController::class, 'update'])->middleware('permission:business-intelligence.update')->name('update');
        Route::delete('/{businessMetric}', [BusinessMetricController::class, 'destroy'])->middleware('permission:business-intelligence.delete')->name('destroy');
    });

