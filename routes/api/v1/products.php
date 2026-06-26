<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Products\Controllers\ProductController;

Route::prefix('products')
    ->name('products.')
    ->group(function () {
        Route::get('/', [ProductController::class, 'index'])
            ->middleware('permission:products.view')
            ->name('index');

        Route::post('/', [ProductController::class, 'store'])
            ->middleware('permission:products.create')
            ->name('store');

        Route::get('/{product}', [ProductController::class, 'show'])
            ->middleware('permission:products.view')
            ->name('show');

        Route::put('/{product}', [ProductController::class, 'update'])
            ->middleware('permission:products.update')
            ->name('update');

        Route::delete('/{product}', [ProductController::class, 'destroy'])
            ->middleware('permission:products.delete')
            ->name('destroy');

        Route::post('/{product}/activate', [ProductController::class, 'activate'])
            ->middleware('permission:products.update')
            ->name('activate');

        Route::post('/{product}/deactivate', [ProductController::class, 'deactivate'])
            ->middleware('permission:products.update')
            ->name('deactivate');
    });

