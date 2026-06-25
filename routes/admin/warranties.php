<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Warranties\Controllers\WarrantyController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/warranties',
        [WarrantyController::class, 'index']
    );

    Route::post(
        '/warranties',
        [WarrantyController::class, 'store']
    );

    Route::get(
        '/warranties/{warranty}',
        [WarrantyController::class, 'show']
    );

    Route::put(
        '/warranties/{warranty}',
        [WarrantyController::class, 'update']
    );

    Route::delete(
        '/warranties/{warranty}',
        [WarrantyController::class, 'destroy']
    );

    Route::post(
        '/warranties/{warranty}/claim',
        [WarrantyController::class, 'claim']
    );

    Route::post(
        '/warranties/{warranty}/expire',
        [WarrantyController::class, 'expire']
    );

    Route::post(
        '/warranties/{warranty}/void',
        [WarrantyController::class, 'void']
    );
});