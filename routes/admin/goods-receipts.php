<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\GoodsReceipts\Controllers\GoodsReceiptController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/goods-receipts',
        [GoodsReceiptController::class, 'index']
    );

    Route::post(
        '/goods-receipts',
        [GoodsReceiptController::class, 'store']
    );

    Route::get(
        '/goods-receipts/{goodsReceipt}',
        [GoodsReceiptController::class, 'show']
    );

    Route::put(
        '/goods-receipts/{goodsReceipt}',
        [GoodsReceiptController::class, 'update']
    );

    Route::post(
        '/goods-receipts/{goodsReceipt}/receive',
        [GoodsReceiptController::class, 'receive']
    );
});