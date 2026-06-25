<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Invoices\Controllers\InvoiceController;

Route::middleware([
    'auth:sanctum',
    'tenant',
])
->prefix('invoices')
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [InvoiceController::class, 'index']
    );

    Route::post(
        '/',
        [InvoiceController::class, 'store']
    );

    Route::get(
        '/{invoice}',
        [InvoiceController::class, 'show']
    );

    Route::put(
        '/{invoice}',
        [InvoiceController::class, 'update']
    );

    Route::delete(
        '/{invoice}',
        [InvoiceController::class, 'destroy']
    );

    /*
    |--------------------------------------------------------------------------
    | Invoice Actions
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/{invoice}/issue',
        [InvoiceController::class, 'issue']
    );

    Route::post(
        '/{invoice}/mark-paid',
        [InvoiceController::class, 'markAsPaid']
    );

    Route::post(
        '/{invoice}/mark-overdue',
        [InvoiceController::class, 'markAsOverdue']
    );

    Route::post(
        '/{invoice}/cancel',
        [InvoiceController::class, 'cancel']
    );

    Route::post(
        '/{invoice}/refund',
        [InvoiceController::class, 'refund']
    );
});
