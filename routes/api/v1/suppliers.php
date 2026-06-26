<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Suppliers\Controllers\SupplierController;

Route::prefix('suppliers')
    ->name('suppliers.')
    ->group(function () {
        Route::get('/', [SupplierController::class, 'index'])
            ->middleware('permission:suppliers.view')
            ->name('index');

        Route::post('/', [SupplierController::class, 'store'])
            ->middleware('permission:suppliers.create')
            ->name('store');

        Route::get('/{supplier}', [SupplierController::class, 'show'])
            ->middleware('permission:suppliers.view')
            ->name('show');

        Route::put('/{supplier}', [SupplierController::class, 'update'])
            ->middleware('permission:suppliers.update')
            ->name('update');

        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])
            ->middleware('permission:suppliers.delete')
            ->name('destroy');

        Route::post('/{supplier}/activate', [SupplierController::class, 'activate'])
            ->middleware('permission:suppliers.update')
            ->name('activate');

        Route::post('/{supplier}/deactivate', [SupplierController::class, 'deactivate'])
            ->middleware('permission:suppliers.update')
            ->name('deactivate');

        Route::post('/{supplier}/block', [SupplierController::class, 'block'])
            ->middleware('permission:suppliers.update')
            ->name('block');
    });

