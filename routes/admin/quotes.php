<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Quotes\Controllers\QuoteController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/quotes',
        [QuoteController::class, 'index']
    );

    Route::post(
        '/quotes',
        [QuoteController::class, 'store']
    );

    Route::get(
        '/quotes/{quote}',
        [QuoteController::class, 'show']
    );

    Route::put(
        '/quotes/{quote}',
        [QuoteController::class, 'update']
    );

    Route::delete(
        '/quotes/{quote}',
        [QuoteController::class, 'destroy']
    );

    Route::post(
        '/quotes/{quote}/approve',
        [QuoteController::class, 'approve']
    );

    Route::post(
        '/quotes/{quote}/reject',
        [QuoteController::class, 'reject']
    );

    Route::post(
        '/quotes/{quote}/cancel',
        [QuoteController::class, 'cancel']
    );
});