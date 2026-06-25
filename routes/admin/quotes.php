<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Quotes\Controllers\QuoteController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/quotes',
        [QuoteController::class, 'index']
    )->middleware('permission:quotes.view');

    Route::post(
        '/quotes',
        [QuoteController::class, 'store']
    )->middleware('permission:quotes.create');

    Route::get(
        '/quotes/{quote}',
        [QuoteController::class, 'show']
    )->middleware('permission:quotes.view');

    Route::put(
        '/quotes/{quote}',
        [QuoteController::class, 'update']
    )->middleware('permission:quotes.update');

    Route::delete(
        '/quotes/{quote}',
        [QuoteController::class, 'destroy']
    )->middleware('permission:quotes.delete');

    Route::post(
        '/quotes/{quote}/approve',
        [QuoteController::class, 'approve']
    )->middleware('permission:quotes.approve');

    Route::post(
        '/quotes/{quote}/reject',
        [QuoteController::class, 'reject']
    )->middleware('permission:quotes.reject');

    Route::post(
        '/quotes/{quote}/cancel',
        [QuoteController::class, 'cancel']
    )->middleware('permission:quotes.cancel');
});
