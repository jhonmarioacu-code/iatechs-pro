<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Products\Controllers\ProductController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/products',
        [ProductController::class, 'index']
)->middleware('permission:products.view');

    Route::post(
        '/products',
        [ProductController::class, 'store']
)->middleware('permission:products.create');

    Route::get(
        '/products/{product}',
        [ProductController::class, 'show']
)->middleware('permission:products.view');

    Route::put(
        '/products/{product}',
        [ProductController::class, 'update']
)->middleware('permission:products.update');

    Route::delete(
        '/products/{product}',
        [ProductController::class, 'destroy']
)->middleware('permission:products.delete');

    Route::post(
        '/products/{product}/activate',
        [ProductController::class, 'activate']
)->middleware('permission:products.update');

    Route::post(
        '/products/{product}/deactivate',
        [ProductController::class, 'deactivate']
)->middleware('permission:products.update');
});
