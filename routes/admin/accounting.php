<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Accounting\Controllers\AccountingController;

Route::middleware([
    'auth'
])
->prefix('accounting')
->group(function () {

    Route::get(
        '/accounts',
        [
            AccountingController::class,
            'accounts'
        ]
    );

    Route::post(
        '/accounts',
        [
            AccountingController::class,
            'storeAccount'
        ]
    );

    Route::put(
        '/accounts/{account}',
        [
            AccountingController::class,
            'updateAccount'
        ]
    );

    Route::get(
        '/journal-entries',
        [
            AccountingController::class,
            'journalEntries'
        ]
    );

    Route::post(
        '/journal-entries',
        [
            AccountingController::class,
            'storeJournalEntry'
        ]
    );

    Route::post(
        '/journal-entries/{journalEntry}/post',
        [
            AccountingController::class,
            'post'
        ]
    );

    Route::post(
        '/journal-entries/{journalEntry}/cancel',
        [
            AccountingController::class,
            'cancel'
        ]
    );
});