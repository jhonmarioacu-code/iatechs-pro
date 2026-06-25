<?php

declare(strict_types=1);

use App\Domains\WorkOrders\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('work-orders')
    ->name('work-orders.')
    ->group(function () {
        Route::get('/', [WorkOrderController::class, 'index'])->name('index');
        Route::post('/', [WorkOrderController::class, 'store'])->name('store');
        Route::get('/{workOrder}', [WorkOrderController::class, 'show'])->name('show');
        Route::put('/{workOrder}', [WorkOrderController::class, 'update'])->name('update');
        Route::delete('/{workOrder}', [WorkOrderController::class, 'destroy'])->name('destroy');
    });
