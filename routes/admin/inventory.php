<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Inventory\Controllers\InventoryController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/inventory',
        [InventoryController::class, 'index']
)->middleware('permission:inventory.view');

    Route::post(
        '/inventory',
        [InventoryController::class, 'store']
)->middleware('permission:inventory.create');

    Route::get(
        '/inventory/{inventoryMovement}',
        [InventoryController::class, 'show']
)->middleware('permission:inventory.view');

    Route::put(
        '/inventory/{inventoryMovement}',
        [InventoryController::class, 'update']
)->middleware('permission:inventory.update');

    Route::delete(
        '/inventory/{inventoryMovement}',
        [InventoryController::class, 'destroy']
)->middleware('permission:inventory.delete');
});
