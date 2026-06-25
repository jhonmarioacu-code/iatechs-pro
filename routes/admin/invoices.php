<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Invoices\Controllers\InvoiceController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/invoices',
        [InvoiceController::class, 'index']
)->middleware('permission:invoices.view');

    Route::post(
        '/invoices',
        [InvoiceController::class, 'store']
)->middleware('permission:invoices.create');

    Route::get(
        '/invoices/{invoice}',
        [InvoiceController::class, 'show']
)->middleware('permission:invoices.view');

    Route::put(
        '/invoices/{invoice}',
        [InvoiceController::class, 'update']
)->middleware('permission:invoices.update');

    Route::delete(
        '/invoices/{invoice}',
        [InvoiceController::class, 'destroy']
)->middleware('permission:invoices.delete');

    Route::post(
        '/invoices/{invoice}/issue',
        [InvoiceController::class, 'issue']
)->middleware('permission:invoices.issue');

    Route::post(
        '/invoices/{invoice}/mark-paid',
        [InvoiceController::class, 'markAsPaid']
)->middleware('permission:invoices.mark_paid');

    Route::post(
        '/invoices/{invoice}/cancel',
        [InvoiceController::class, 'cancel']
)->middleware('permission:invoices.cancel');
});
