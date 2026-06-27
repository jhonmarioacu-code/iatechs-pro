<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Inventory\Controllers\InventoryController;
use App\Domains\Inventory\Controllers\StockTransferController;

Route::prefix('inventory')
    ->name('inventory.')
    ->group(function () {
        Route::get('/', [InventoryController::class, 'index'])
            ->middleware('permission:inventory.view')
            ->name('movements.index');

        Route::post('/', [InventoryController::class, 'store'])
            ->middleware('permission:inventory.create')
            ->name('movements.store');

        Route::get('/{inventoryMovement}', [InventoryController::class, 'show'])
            ->middleware('permission:inventory.view')
            ->name('movements.show');

        Route::put('/{inventoryMovement}', [InventoryController::class, 'update'])
            ->middleware('permission:inventory.update')
            ->name('movements.update');

        Route::delete('/{inventoryMovement}', [InventoryController::class, 'destroy'])
            ->middleware('permission:inventory.delete')
            ->name('movements.destroy');
    });

Route::prefix('stock-transfers')
    ->name('stock-transfers.')
    ->group(function () {
        Route::get('/', [StockTransferController::class, 'index'])
            ->middleware('permission:inventory.view')
            ->name('index');

        Route::post('/', [StockTransferController::class, 'store'])
            ->middleware('permission:inventory.create')
            ->name('store');

        Route::get('/{stockTransfer}', [StockTransferController::class, 'show'])
            ->middleware('permission:inventory.view')
            ->name('show');

        Route::put('/{stockTransfer}', [StockTransferController::class, 'update'])
            ->middleware('permission:inventory.update')
            ->name('update');

        Route::post('/{stockTransfer}/approve', [StockTransferController::class, 'approve'])
            ->middleware('permission:inventory.approve')
            ->name('approve');

        Route::post('/{stockTransfer}/complete', [StockTransferController::class, 'complete'])
            ->middleware('permission:inventory.complete')
            ->name('complete');

        Route::post('/{stockTransfer}/cancel', [StockTransferController::class, 'cancel'])
            ->middleware('permission:inventory.cancel')
            ->name('cancel');
    });

