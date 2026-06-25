<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Customers\Controllers\CustomerController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/customers',
        [CustomerController::class, 'index']
)->middleware('permission:customers.view');

    Route::post(
        '/customers',
        [CustomerController::class, 'store']
)->middleware('permission:customers.create');

    Route::get(
        '/customers/{customer}',
        [CustomerController::class, 'show']
)->middleware('permission:customers.view');

    Route::put(
        '/customers/{customer}',
        [CustomerController::class, 'update']
)->middleware('permission:customers.update');

    Route::delete(
        '/customers/{customer}',
        [CustomerController::class, 'destroy']
)->middleware('permission:customers.delete');

    Route::post(
        '/customers/{customer}/activate',
        [CustomerController::class, 'activate']
)->middleware('permission:customers.update');

    Route::post(
        '/customers/{customer}/deactivate',
        [CustomerController::class, 'deactivate']
)->middleware('permission:customers.update');
});
