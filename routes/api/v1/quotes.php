<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Quotes\Controllers\QuoteController;

Route::prefix('quotes')
    ->name('quotes.')
    ->group(function () {
        Route::get('/', [QuoteController::class, 'index'])
            ->middleware('permission:quotes.view')
            ->name('index');

        Route::post('/', [QuoteController::class, 'store'])
            ->middleware('permission:quotes.create')
            ->name('store');

        Route::get('/{quote}', [QuoteController::class, 'show'])
            ->middleware('permission:quotes.view')
            ->name('show');

        Route::put('/{quote}', [QuoteController::class, 'update'])
            ->middleware('permission:quotes.update')
            ->name('update');

        Route::delete('/{quote}', [QuoteController::class, 'destroy'])
            ->middleware('permission:quotes.delete')
            ->name('destroy');

        Route::post('/{quote}/approve', [QuoteController::class, 'approve'])
            ->middleware('permission:quotes.approve')
            ->name('approve');

        Route::post('/{quote}/reject', [QuoteController::class, 'reject'])
            ->middleware('permission:quotes.reject')
            ->name('reject');

        Route::post('/{quote}/cancel', [QuoteController::class, 'cancel'])
            ->middleware('permission:quotes.cancel')
            ->name('cancel');
    });

