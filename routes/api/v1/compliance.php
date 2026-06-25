<?php

declare(strict_types=1);

use App\Domains\Compliance\Controllers\ComplianceRecordController;
use Illuminate\Support\Facades\Route;

Route::prefix('compliance')
    ->name('compliance.')
    ->group(function () {
        Route::get('/', [ComplianceRecordController::class, 'index'])
            ->middleware('permission:compliance.view')
            ->name('index');

        Route::post('/', [ComplianceRecordController::class, 'store'])
            ->middleware('permission:compliance.create')
            ->name('store');

        Route::get('/{complianceRecord}', [ComplianceRecordController::class, 'show'])
            ->middleware('permission:compliance.view')
            ->name('show');

        Route::put('/{complianceRecord}', [ComplianceRecordController::class, 'update'])
            ->middleware('permission:compliance.update')
            ->name('update');

        Route::delete('/{complianceRecord}', [ComplianceRecordController::class, 'destroy'])
            ->middleware('permission:compliance.delete')
            ->name('destroy');
    });
