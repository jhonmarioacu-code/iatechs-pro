<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\GoodsReceipts\Controllers\GoodsReceiptController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/goods-receipts',
        [GoodsReceiptController::class, 'index']
)->middleware('permission:goods-receipts.view');

    Route::post(
        '/goods-receipts',
        [GoodsReceiptController::class, 'store']
)->middleware('permission:goods-receipts.create');

    Route::get(
        '/goods-receipts/{goodsReceipt}',
        [GoodsReceiptController::class, 'show']
)->middleware('permission:goods-receipts.view');

    Route::put(
        '/goods-receipts/{goodsReceipt}',
        [GoodsReceiptController::class, 'update']
)->middleware('permission:goods-receipts.update');

    Route::post(
        '/goods-receipts/{goodsReceipt}/receive',
        [GoodsReceiptController::class, 'receive']
)->middleware('permission:goods-receipts.create');
});
