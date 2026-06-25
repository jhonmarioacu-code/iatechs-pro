<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Accounting\Controllers\AccountingController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
->prefix('accounting')
->group(function () {

    Route::get(
        '/accounts',
        [
            AccountingController::class,
            'accounts'
        ]
    )->middleware('permission:accounting.view');

    Route::post(
        '/accounts',
        [
            AccountingController::class,
            'storeAccount'
        ]
    )->middleware('permission:accounting.create');

    Route::put(
        '/accounts/{account}',
        [
            AccountingController::class,
            'updateAccount'
        ]
    )->middleware('permission:accounting.update');

    Route::get(
        '/journal-entries',
        [
            AccountingController::class,
            'journalEntries'
        ]
    )->middleware('permission:accounting.view');

    Route::post(
        '/journal-entries',
        [
            AccountingController::class,
            'storeJournalEntry'
        ]
    )->middleware('permission:accounting.create');

    Route::post(
        '/journal-entries/{journalEntry}/post',
        [
            AccountingController::class,
            'post'
        ]
    )->middleware('permission:accounting.post');

    Route::post(
        '/journal-entries/{journalEntry}/cancel',
        [
            AccountingController::class,
            'cancel'
        ]
    )->middleware('permission:accounting.cancel');
});
