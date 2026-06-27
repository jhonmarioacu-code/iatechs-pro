<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Subscriptions\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Subscriptions
|--------------------------------------------------------------------------
*/

Route::middleware([
    'tenant'
])

->prefix('subscriptions')

->name('subscriptions.')

->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [SubscriptionController::class, 'index']
    )
    ->middleware('permission:subscriptions.view')
    ->name('index');

    Route::post(
        '/',
        [SubscriptionController::class, 'store']
    )
    ->middleware('permission:subscriptions.create')
    ->name('store');

    Route::get(
        '/{subscription}',
        [SubscriptionController::class, 'show']
    )
    ->middleware('permission:subscriptions.view')
    ->name('show');

    Route::put(
        '/{subscription}',
        [SubscriptionController::class, 'update']
    )
    ->middleware('permission:subscriptions.update')
    ->name('update');

    Route::delete(
        '/{subscription}',
        [SubscriptionController::class, 'destroy']
    )
    ->middleware('permission:subscriptions.delete')
    ->name('destroy');

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/{subscription}/activate',
        [SubscriptionController::class, 'activate']
    )
    ->middleware('permission:subscriptions.activate')
    ->name('activate');

    Route::patch(
        '/{subscription}/cancel',
        [SubscriptionController::class, 'cancel']
    )
    ->middleware('permission:subscriptions.cancel')
    ->name('cancel');

    Route::patch(
        '/{subscription}/renew',
        [SubscriptionController::class, 'renew']
    )
    ->middleware('permission:subscriptions.renew')
    ->name('renew');

    Route::patch(
        '/{subscription}/change-plan',
        [SubscriptionController::class, 'changePlan']
    )
    ->middleware('permission:subscriptions.change-plan')
    ->name('change-plan');

    Route::post(
        '/{subscription}/checkout',
        [SubscriptionController::class, 'checkout']
    )
    ->middleware('permission:subscriptions.update')
    ->name('checkout');
});
