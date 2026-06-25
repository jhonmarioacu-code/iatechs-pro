<?php

declare(strict_types=1);

use App\Domains\ServiceContracts\Controllers\ServiceContractController;
use Illuminate\Support\Facades\Route;

Route::prefix('service-contracts')
    ->name('service-contracts.')
    ->group(function () {
        Route::get('/', [ServiceContractController::class, 'index'])
            ->middleware('permission:service-contracts.view')
            ->name('index');

        Route::post('/', [ServiceContractController::class, 'store'])
            ->middleware('permission:service-contracts.create')
            ->name('store');

        Route::get('/{serviceContract}', [ServiceContractController::class, 'show'])
            ->middleware('permission:service-contracts.view')
            ->name('show');

        Route::put('/{serviceContract}', [ServiceContractController::class, 'update'])
            ->middleware('permission:service-contracts.update')
            ->name('update');

        Route::delete('/{serviceContract}', [ServiceContractController::class, 'destroy'])
            ->middleware('permission:service-contracts.delete')
            ->name('destroy');
    });
