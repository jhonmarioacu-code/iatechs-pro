<?php

declare(strict_types=1);

use App\Domains\Analytics\Controllers\AnalyticController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
    ->prefix('analytics')
    ->name('analytics.')
    ->group(function () {
        Route::get('/', [AnalyticController::class, 'index'])->middleware('permission:analytics.view')->name('index');
        Route::post('/', [AnalyticController::class, 'store'])->middleware('permission:analytics.create')->name('store');
        Route::get('/{analytic}', [AnalyticController::class, 'show'])->middleware('permission:analytics.view')->name('show');
        Route::put('/{analytic}', [AnalyticController::class, 'update'])->middleware('permission:analytics.update')->name('update');
        Route::delete('/{analytic}', [AnalyticController::class, 'destroy'])->middleware('permission:analytics.delete')->name('destroy');
    });

