<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Payments\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    'tenant',
])
->prefix('payments')
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [PaymentController::class, 'index']
    );

    Route::post(
        '/',
        [PaymentController::class, 'store']
    );

    Route::get(
        '/{payment}',
        [PaymentController::class, 'show']
    );

    Route::put(
        '/{payment}',
        [PaymentController::class, 'update']
    );

    Route::delete(
        '/{payment}',
        [PaymentController::class, 'destroy']
    );

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/{payment}/complete',
        [PaymentController::class, 'complete']
    );

    Route::post(
        '/{payment}/cancel',
        [PaymentController::class, 'cancel']
    );

    Route::post(
        '/{payment}/refund',
        [PaymentController::class, 'refund']
    );
});