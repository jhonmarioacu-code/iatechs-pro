<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Invoices\Controllers\InvoiceItemController;

/*
|--------------------------------------------------------------------------
| Invoice Items API
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum'
])

->prefix('invoice-items')

->group(function () {

    Route::get(
        '/',
        [InvoiceItemController::class, 'index']
    )
    ->middleware(
        'permission:invoices.view'
    );

    Route::post(
        '/',
        [InvoiceItemController::class, 'store']
    )
    ->middleware(
        'permission:invoices.create'
    );

    Route::get(
        '/{invoiceItem}',
        [InvoiceItemController::class, 'show']
    )
    ->middleware(
        'permission:invoices.view'
    );

    Route::put(
        '/{invoiceItem}',
        [InvoiceItemController::class, 'update']
    )
    ->middleware(
        'permission:invoices.update'
    );

    Route::delete(
        '/{invoiceItem}',
        [InvoiceItemController::class, 'destroy']
    )
    ->middleware(
        'permission:invoices.delete'
    );
});