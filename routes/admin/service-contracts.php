<?php

declare(strict_types=1);

use App\Domains\ServiceContracts\Controllers\ServiceContractController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('service-contracts')
    ->name('service-contracts.')
    ->group(function () {
        Route::get('/', [ServiceContractController::class, 'index'])->name('index');
        Route::post('/', [ServiceContractController::class, 'store'])->name('store');
        Route::get('/{serviceContract}', [ServiceContractController::class, 'show'])->name('show');
        Route::put('/{serviceContract}', [ServiceContractController::class, 'update'])->name('update');
        Route::delete('/{serviceContract}', [ServiceContractController::class, 'destroy'])->name('destroy');
    });
