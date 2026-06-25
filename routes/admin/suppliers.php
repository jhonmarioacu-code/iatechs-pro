<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Suppliers\Controllers\SupplierController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/suppliers',
        [SupplierController::class, 'index']
    );

    Route::post(
        '/suppliers',
        [SupplierController::class, 'store']
    );

    Route::get(
        '/suppliers/{supplier}',
        [SupplierController::class, 'show']
    );

    Route::put(
        '/suppliers/{supplier}',
        [SupplierController::class, 'update']
    );

    Route::delete(
        '/suppliers/{supplier}',
        [SupplierController::class, 'destroy']
    );

    Route::post(
        '/suppliers/{supplier}/activate',
        [SupplierController::class, 'activate']
    );

    Route::post(
        '/suppliers/{supplier}/deactivate',
        [SupplierController::class, 'deactivate']
    );

    Route::post(
        '/suppliers/{supplier}/block',
        [SupplierController::class, 'block']
    );
});