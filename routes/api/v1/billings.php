<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Billing\Controllers\BillingController;

/*
|--------------------------------------------------------------------------
| Billings API v1
|--------------------------------------------------------------------------
*/

Route::prefix('billings')
    ->name('billings.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CRUD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [BillingController::class, 'index']
        )
        ->middleware('permission:billings.view')
        ->name('index');

        Route::post(
            '/',
            [BillingController::class, 'store']
        )
        ->middleware('permission:billings.create')
        ->name('store');

        Route::get(
            '/{billing}',
            [BillingController::class, 'show']
        )
        ->middleware('permission:billings.view')
        ->name('show');

        Route::put(
            '/{billing}',
            [BillingController::class, 'update']
        )
        ->middleware('permission:billings.update')
        ->name('update');

        Route::delete(
            '/{billing}',
            [BillingController::class, 'destroy']
        )
        ->middleware('permission:billings.delete')
        ->name('destroy');

        /*
        |--------------------------------------------------------------------------
        | Lifecycle Actions
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{billing}/mark-paid',
            [BillingController::class, 'markPaid']
        )
        ->middleware('permission:billings.mark-paid')
        ->name('mark-paid');

        Route::patch(
            '/{billing}/mark-failed',
            [BillingController::class, 'markFailed']
        )
        ->middleware('permission:billings.mark-failed')
        ->name('mark-failed');

        Route::patch(
            '/{billing}/cancel',
            [BillingController::class, 'cancel']
        )
        ->middleware('permission:billings.cancel')
        ->name('cancel');

        Route::patch(
            '/{billing}/refund',
            [BillingController::class, 'refund']
        )
        ->middleware('permission:billings.refund')
        ->name('refund');
    });
