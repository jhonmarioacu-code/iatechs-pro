<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Subscriptions\Controllers\SubscriptionController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/subscriptions',
        [SubscriptionController::class, 'index']
    )->middleware('permission:subscriptions.view');

    Route::post(
        '/subscriptions',
        [SubscriptionController::class, 'store']
    )->middleware('permission:subscriptions.create');

    Route::get(
        '/subscriptions/{subscription}',
        [SubscriptionController::class, 'show']
    )->middleware('permission:subscriptions.view');

    Route::put(
        '/subscriptions/{subscription}',
        [SubscriptionController::class, 'update']
    )->middleware('permission:subscriptions.update');

    Route::delete(
        '/subscriptions/{subscription}',
        [SubscriptionController::class, 'destroy']
    )->middleware('permission:subscriptions.delete');
});
