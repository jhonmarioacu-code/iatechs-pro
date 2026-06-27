<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\PurchaseOrders\Controllers\PurchaseOrderController;

Route::prefix('purchase-orders')
    ->name('purchase-orders.')
    ->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])
            ->middleware('permission:purchase-orders.view')
            ->name('index');

        Route::post('/', [PurchaseOrderController::class, 'store'])
            ->middleware('permission:purchase-orders.create')
            ->name('store');

        Route::get('/{purchaseOrder}', [PurchaseOrderController::class, 'show'])
            ->middleware('permission:purchase-orders.view')
            ->name('show');

        Route::put('/{purchaseOrder}', [PurchaseOrderController::class, 'update'])
            ->middleware('permission:purchase-orders.update')
            ->name('update');

        Route::post('/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])
            ->middleware('permission:purchase-orders.approve')
            ->name('approve');

        Route::post('/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])
            ->middleware('permission:purchase-orders.update')
            ->name('receive');

        Route::post('/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])
            ->middleware('permission:purchase-orders.cancel')
            ->name('cancel');
    });

