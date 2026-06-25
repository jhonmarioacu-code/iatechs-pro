<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Customers\Controllers\CustomerController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/customers',
        [CustomerController::class, 'index']
    );

    Route::post(
        '/customers',
        [CustomerController::class, 'store']
    );

    Route::get(
        '/customers/{customer}',
        [CustomerController::class, 'show']
    );

    Route::put(
        '/customers/{customer}',
        [CustomerController::class, 'update']
    );

    Route::delete(
        '/customers/{customer}',
        [CustomerController::class, 'destroy']
    );

    Route::post(
        '/customers/{customer}/activate',
        [CustomerController::class, 'activate']
    );

    Route::post(
        '/customers/{customer}/deactivate',
        [CustomerController::class, 'deactivate']
    );
});