<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\GoodsReceipts\Controllers\GoodsReceiptController;

Route::prefix('goods-receipts')
    ->name('goods-receipts.')
    ->group(function () {
        Route::get('/', [GoodsReceiptController::class, 'index'])
            ->middleware('permission:goods-receipts.view')
            ->name('index');

        Route::post('/', [GoodsReceiptController::class, 'store'])
            ->middleware('permission:goods-receipts.create')
            ->name('store');

        Route::get('/{goodsReceipt}', [GoodsReceiptController::class, 'show'])
            ->middleware('permission:goods-receipts.view')
            ->name('show');

        Route::put('/{goodsReceipt}', [GoodsReceiptController::class, 'update'])
            ->middleware('permission:goods-receipts.update')
            ->name('update');

        Route::post('/{goodsReceipt}/receive', [GoodsReceiptController::class, 'receive'])
            ->middleware('permission:goods-receipts.update')
            ->name('receive');
    });

