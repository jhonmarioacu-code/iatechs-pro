<?php

declare(strict_types=1);

use App\Domains\Purchases\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
    ->prefix('purchases')
    ->name('purchases.')
    ->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->middleware('permission:purchases.view')->name('index');
        Route::post('/', [PurchaseController::class, 'store'])->middleware('permission:purchases.create')->name('store');
        Route::get('/{purchase}', [PurchaseController::class, 'show'])->middleware('permission:purchases.view')->name('show');
        Route::put('/{purchase}', [PurchaseController::class, 'update'])->middleware('permission:purchases.update')->name('update');
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy'])->middleware('permission:purchases.delete')->name('destroy');
    });

