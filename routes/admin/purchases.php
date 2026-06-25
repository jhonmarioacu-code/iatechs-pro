<?php

declare(strict_types=1);

use App\Domains\Purchases\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('purchases')
    ->name('purchases.')
    ->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('index');
        Route::post('/', [PurchaseController::class, 'store'])->name('store');
        Route::get('/{purchase}', [PurchaseController::class, 'show'])->name('show');
        Route::put('/{purchase}', [PurchaseController::class, 'update'])->name('update');
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy'])->name('destroy');
    });
