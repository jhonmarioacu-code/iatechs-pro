<?php

declare(strict_types=1);

use App\Domains\WorkOrders\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
    ->prefix('work-orders')
    ->name('work-orders.')
    ->group(function () {
        Route::get('/', [WorkOrderController::class, 'index'])->middleware('permission:work-orders.view')->name('index');
        Route::post('/', [WorkOrderController::class, 'store'])->middleware('permission:work-orders.create')->name('store');
        Route::get('/{workOrder}', [WorkOrderController::class, 'show'])->middleware('permission:work-orders.view')->name('show');
        Route::put('/{workOrder}', [WorkOrderController::class, 'update'])->middleware('permission:work-orders.update')->name('update');
        Route::delete('/{workOrder}', [WorkOrderController::class, 'destroy'])->middleware('permission:work-orders.delete')->name('destroy');
    });

