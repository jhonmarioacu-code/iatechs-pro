<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Inventory\Controllers\StockTransferController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/stock-transfers',
        [StockTransferController::class, 'index']
    );

    Route::post(
        '/stock-transfers',
        [StockTransferController::class, 'store']
    );

    Route::get(
        '/stock-transfers/{stockTransfer}',
        [StockTransferController::class, 'show']
    );

    Route::put(
        '/stock-transfers/{stockTransfer}',
        [StockTransferController::class, 'update']
    );

    Route::post(
        '/stock-transfers/{stockTransfer}/approve',
        [StockTransferController::class, 'approve']
    );

    Route::post(
        '/stock-transfers/{stockTransfer}/complete',
        [StockTransferController::class, 'complete']
    );

    Route::post(
        '/stock-transfers/{stockTransfer}/cancel',
        [StockTransferController::class, 'cancel']
    );
});