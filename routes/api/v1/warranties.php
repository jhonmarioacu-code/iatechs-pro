<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Warranties\Controllers\WarrantyController;

Route::prefix('warranties')
    ->name('warranties.')
    ->group(function () {
        Route::get('/', [WarrantyController::class, 'index'])
            ->middleware('permission:warranties.view')
            ->name('index');

        Route::post('/', [WarrantyController::class, 'store'])
            ->middleware('permission:warranties.create')
            ->name('store');

        Route::get('/{warranty}', [WarrantyController::class, 'show'])
            ->middleware('permission:warranties.view')
            ->name('show');

        Route::put('/{warranty}', [WarrantyController::class, 'update'])
            ->middleware('permission:warranties.update')
            ->name('update');

        Route::delete('/{warranty}', [WarrantyController::class, 'destroy'])
            ->middleware('permission:warranties.delete')
            ->name('destroy');

        Route::post('/{warranty}/claim', [WarrantyController::class, 'claim'])
            ->middleware('permission:warranties.claim')
            ->name('claim');

        Route::post('/{warranty}/expire', [WarrantyController::class, 'expire'])
            ->middleware('permission:warranties.expire')
            ->name('expire');

        Route::post('/{warranty}/void', [WarrantyController::class, 'void'])
            ->middleware('permission:warranties.void')
            ->name('void');
    });

