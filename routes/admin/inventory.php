<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Inventory\Controllers\InventoryController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/inventory',
        [InventoryController::class, 'index']
    );

    Route::post(
        '/inventory',
        [InventoryController::class, 'store']
    );

    Route::get(
        '/inventory/{inventoryMovement}',
        [InventoryController::class, 'show']
    );

    Route::put(
        '/inventory/{inventoryMovement}',
        [InventoryController::class, 'update']
    );

    Route::delete(
        '/inventory/{inventoryMovement}',
        [InventoryController::class, 'destroy']
    );
});