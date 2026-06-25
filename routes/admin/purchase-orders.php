<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\PurchaseOrders\Controllers\PurchaseOrderController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/purchase-orders',
        [PurchaseOrderController::class, 'index']
)->middleware('permission:purchase-orders.view');

    Route::post(
        '/purchase-orders',
        [PurchaseOrderController::class, 'store']
)->middleware('permission:purchase-orders.create');

    Route::get(
        '/purchase-orders/{purchaseOrder}',
        [PurchaseOrderController::class, 'show']
)->middleware('permission:purchase-orders.view');

    Route::put(
        '/purchase-orders/{purchaseOrder}',
        [PurchaseOrderController::class, 'update']
)->middleware('permission:purchase-orders.update');

    Route::post(
        '/purchase-orders/{purchaseOrder}/approve',
        [PurchaseOrderController::class, 'approve']
)->middleware('permission:purchase-orders.approve');

    Route::post(
        '/purchase-orders/{purchaseOrder}/receive',
        [PurchaseOrderController::class, 'receive']
)->middleware('permission:purchase-orders.update');

    Route::post(
        '/purchase-orders/{purchaseOrder}/cancel',
        [PurchaseOrderController::class, 'cancel']
)->middleware('permission:purchase-orders.cancel');
});
