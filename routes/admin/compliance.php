<?php

declare(strict_types=1);

use App\Domains\Compliance\Controllers\ComplianceRecordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('compliance')
    ->name('compliance.')
    ->group(function () {
        Route::get('/', [ComplianceRecordController::class, 'index'])->name('index');
        Route::post('/', [ComplianceRecordController::class, 'store'])->name('store');
        Route::get('/{complianceRecord}', [ComplianceRecordController::class, 'show'])->name('show');
        Route::put('/{complianceRecord}', [ComplianceRecordController::class, 'update'])->name('update');
        Route::delete('/{complianceRecord}', [ComplianceRecordController::class, 'destroy'])->name('destroy');
    });
