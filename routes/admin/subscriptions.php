<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Subscriptions\Controllers\SubscriptionController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/subscriptions',
        [SubscriptionController::class, 'index']
    );

    Route::post(
        '/subscriptions',
        [SubscriptionController::class, 'store']
    );

    Route::get(
        '/subscriptions/{subscription}',
        [SubscriptionController::class, 'show']
    );

    Route::put(
        '/subscriptions/{subscription}',
        [SubscriptionController::class, 'update']
    );

    Route::delete(
        '/subscriptions/{subscription}',
        [SubscriptionController::class, 'destroy']
    );
});