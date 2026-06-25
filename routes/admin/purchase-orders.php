<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\PurchaseOrders\Controllers\PurchaseOrderController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/purchase-orders',
        [PurchaseOrderController::class, 'index']
    );

    Route::post(
        '/purchase-orders',
        [PurchaseOrderController::class, 'store']
    );

    Route::get(
        '/purchase-orders/{purchaseOrder}',
        [PurchaseOrderController::class, 'show']
    );

    Route::put(
        '/purchase-orders/{purchaseOrder}',
        [PurchaseOrderController::class, 'update']
    );

    Route::post(
        '/purchase-orders/{purchaseOrder}/approve',
        [PurchaseOrderController::class, 'approve']
    );

    Route::post(
        '/purchase-orders/{purchaseOrder}/receive',
        [PurchaseOrderController::class, 'receive']
    );

    Route::post(
        '/purchase-orders/{purchaseOrder}/cancel',
        [PurchaseOrderController::class, 'cancel']
    );
});