<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Warranties\Controllers\WarrantyController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/warranties',
        [WarrantyController::class, 'index']
)->middleware('permission:repairs.view');

    Route::post(
        '/warranties',
        [WarrantyController::class, 'store']
)->middleware('permission:repairs.create');

    Route::get(
        '/warranties/{warranty}',
        [WarrantyController::class, 'show']
)->middleware('permission:repairs.view');

    Route::put(
        '/warranties/{warranty}',
        [WarrantyController::class, 'update']
)->middleware('permission:repairs.update');

    Route::delete(
        '/warranties/{warranty}',
        [WarrantyController::class, 'destroy']
)->middleware('permission:repairs.delete');

    Route::post(
        '/warranties/{warranty}/claim',
        [WarrantyController::class, 'claim']
)->middleware('permission:repairs.update');

    Route::post(
        '/warranties/{warranty}/expire',
        [WarrantyController::class, 'expire']
)->middleware('permission:repairs.update');

    Route::post(
        '/warranties/{warranty}/void',
        [WarrantyController::class, 'void']
)->middleware('permission:repairs.update');
});
