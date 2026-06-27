<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Accounting\Controllers\AccountingController;

Route::prefix('accounting')
    ->name('accounting.')
    ->group(function () {
        Route::get('/accounts', [AccountingController::class, 'accounts'])
            ->middleware('permission:accounting.view')
            ->name('accounts.index');

        Route::post('/accounts', [AccountingController::class, 'storeAccount'])
            ->middleware('permission:accounting.create')
            ->name('accounts.store');

        Route::put('/accounts/{account}', [AccountingController::class, 'updateAccount'])
            ->middleware('permission:accounting.update')
            ->name('accounts.update');

        Route::get('/journal-entries', [AccountingController::class, 'journalEntries'])
            ->middleware('permission:accounting.view')
            ->name('journal-entries.index');

        Route::post('/journal-entries', [AccountingController::class, 'storeJournalEntry'])
            ->middleware('permission:accounting.create')
            ->name('journal-entries.store');

        Route::post('/journal-entries/{journalEntry}/post', [AccountingController::class, 'post'])
            ->middleware('permission:accounting.post')
            ->name('journal-entries.post');

        Route::post('/journal-entries/{journalEntry}/cancel', [AccountingController::class, 'cancel'])
            ->middleware('permission:accounting.cancel')
            ->name('journal-entries.cancel');
    });

