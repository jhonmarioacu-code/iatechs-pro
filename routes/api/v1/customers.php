<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Customers\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Customers API
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    'tenant'
])

->prefix('customers')

->name('customers.')

->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [CustomerController::class, 'index']
    )->name('index');

    Route::post(
        '/',
        [CustomerController::class, 'store']
    )->name('store');

    Route::get(
        '/{customer}',
        [CustomerController::class, 'show']
    )->name('show');

    Route::put(
        '/{customer}',
        [CustomerController::class, 'update']
    )->name('update');

    Route::delete(
        '/{customer}',
        [CustomerController::class, 'destroy']
    )->name('destroy');

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/{customer}/activate',
        [CustomerController::class, 'activate']
    )->name('activate');

    Route::post(
        '/{customer}/deactivate',
        [CustomerController::class, 'deactivate']
    )->name('deactivate');
});