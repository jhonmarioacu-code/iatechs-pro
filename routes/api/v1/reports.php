<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Reports\Controllers\ReportController;

Route::prefix('reports')
    ->name('reports.')
    ->group(function () {
        Route::get('/', [ReportController::class, 'index'])
            ->middleware('permission:reports.view')
            ->name('index');

        Route::post('/', [ReportController::class, 'store'])
            ->middleware('permission:reports.export')
            ->name('store');

        Route::get('/{report}', [ReportController::class, 'show'])
            ->middleware('permission:reports.view')
            ->name('show');
    });

