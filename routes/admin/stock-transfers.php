<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Inventory\Controllers\StockTransferController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/stock-transfers',
        [StockTransferController::class, 'index']
)->middleware('permission:inventory.view');

    Route::post(
        '/stock-transfers',
        [StockTransferController::class, 'store']
)->middleware('permission:inventory.create');

    Route::get(
        '/stock-transfers/{stockTransfer}',
        [StockTransferController::class, 'show']
)->middleware('permission:inventory.view');

    Route::put(
        '/stock-transfers/{stockTransfer}',
        [StockTransferController::class, 'update']
)->middleware('permission:inventory.update');

    Route::post(
        '/stock-transfers/{stockTransfer}/approve',
        [StockTransferController::class, 'approve']
)->middleware('permission:inventory.approve');

    Route::post(
        '/stock-transfers/{stockTransfer}/complete',
        [StockTransferController::class, 'complete']
)->middleware('permission:inventory.complete');

    Route::post(
        '/stock-transfers/{stockTransfer}/cancel',
        [StockTransferController::class, 'cancel']
)->middleware('permission:inventory.cancel');
});
