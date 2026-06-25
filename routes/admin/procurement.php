<?php

declare(strict_types=1);

use App\Domains\Procurement\Controllers\ProcurementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('procurement')
    ->name('procurement.')
    ->group(function () {
        Route::get('/', [ProcurementController::class, 'index'])->name('index');
        Route::post('/', [ProcurementController::class, 'store'])->name('store');
        Route::get('/{procurement}', [ProcurementController::class, 'show'])->name('show');
        Route::put('/{procurement}', [ProcurementController::class, 'update'])->name('update');
        Route::delete('/{procurement}', [ProcurementController::class, 'destroy'])->name('destroy');
    });
