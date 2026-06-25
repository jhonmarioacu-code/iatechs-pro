<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Products\Controllers\ProductController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/products',
        [ProductController::class, 'index']
    );

    Route::post(
        '/products',
        [ProductController::class, 'store']
    );

    Route::get(
        '/products/{product}',
        [ProductController::class, 'show']
    );

    Route::put(
        '/products/{product}',
        [ProductController::class, 'update']
    );

    Route::delete(
        '/products/{product}',
        [ProductController::class, 'destroy']
    );

    Route::post(
        '/products/{product}/activate',
        [ProductController::class, 'activate']
    );

    Route::post(
        '/products/{product}/deactivate',
        [ProductController::class, 'deactivate']
    );
});