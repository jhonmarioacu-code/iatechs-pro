<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Payments\Controllers\PaymentController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/payments',
        [PaymentController::class, 'index']
    );

    Route::post(
        '/payments',
        [PaymentController::class, 'store']
    );

    Route::get(
        '/payments/{payment}',
        [PaymentController::class, 'show']
    );

    Route::put(
        '/payments/{payment}',
        [PaymentController::class, 'update']
    );

    Route::delete(
        '/payments/{payment}',
        [PaymentController::class, 'destroy']
    );

    Route::post(
        '/payments/{payment}/complete',
        [PaymentController::class, 'complete']
    );

    Route::post(
        '/payments/{payment}/refund',
        [PaymentController::class, 'refund']
    );

    Route::post(
        '/payments/{payment}/cancel',
        [PaymentController::class, 'cancel']
    );
});