<?php

declare(strict_types=1);

use App\Domains\Procurement\Controllers\ProcurementController;
use Illuminate\Support\Facades\Route;

Route::prefix('procurement')
    ->name('procurement.')
    ->group(function () {
        Route::get('/', [ProcurementController::class, 'index'])
            ->middleware('permission:procurement.view')
            ->name('index');

        Route::post('/', [ProcurementController::class, 'store'])
            ->middleware('permission:procurement.create')
            ->name('store');

        Route::get('/{procurement}', [ProcurementController::class, 'show'])
            ->middleware('permission:procurement.view')
            ->name('show');

        Route::put('/{procurement}', [ProcurementController::class, 'update'])
            ->middleware('permission:procurement.update')
            ->name('update');

        Route::delete('/{procurement}', [ProcurementController::class, 'destroy'])
            ->middleware('permission:procurement.delete')
            ->name('destroy');
    });
