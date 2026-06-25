<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Invoices\Controllers\InvoiceController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/invoices',
        [InvoiceController::class, 'index']
    );

    Route::post(
        '/invoices',
        [InvoiceController::class, 'store']
    );

    Route::get(
        '/invoices/{invoice}',
        [InvoiceController::class, 'show']
    );

    Route::put(
        '/invoices/{invoice}',
        [InvoiceController::class, 'update']
    );

    Route::delete(
        '/invoices/{invoice}',
        [InvoiceController::class, 'destroy']
    );

    Route::post(
        '/invoices/{invoice}/issue',
        [InvoiceController::class, 'issue']
    );

    Route::post(
        '/invoices/{invoice}/mark-paid',
        [InvoiceController::class, 'markAsPaid']
    );

    Route::post(
        '/invoices/{invoice}/cancel',
        [InvoiceController::class, 'cancel']
    );
});