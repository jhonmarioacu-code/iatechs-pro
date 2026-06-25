<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Suppliers\Controllers\SupplierController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/suppliers',
        [SupplierController::class, 'index']
)->middleware('permission:suppliers.view');

    Route::post(
        '/suppliers',
        [SupplierController::class, 'store']
)->middleware('permission:suppliers.create');

    Route::get(
        '/suppliers/{supplier}',
        [SupplierController::class, 'show']
)->middleware('permission:suppliers.view');

    Route::put(
        '/suppliers/{supplier}',
        [SupplierController::class, 'update']
)->middleware('permission:suppliers.update');

    Route::delete(
        '/suppliers/{supplier}',
        [SupplierController::class, 'destroy']
)->middleware('permission:suppliers.delete');

    Route::post(
        '/suppliers/{supplier}/activate',
        [SupplierController::class, 'activate']
)->middleware('permission:suppliers.update');

    Route::post(
        '/suppliers/{supplier}/deactivate',
        [SupplierController::class, 'deactivate']
)->middleware('permission:suppliers.update');

    Route::post(
        '/suppliers/{supplier}/block',
        [SupplierController::class, 'block']
)->middleware('permission:suppliers.update');
});
