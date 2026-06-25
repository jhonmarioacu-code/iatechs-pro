<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Payments\Controllers\PaymentController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/payments',
        [PaymentController::class, 'index']
)->middleware('permission:payments.view');

    Route::post(
        '/payments',
        [PaymentController::class, 'store']
)->middleware('permission:payments.create');

    Route::get(
        '/payments/{payment}',
        [PaymentController::class, 'show']
)->middleware('permission:payments.view');

    Route::put(
        '/payments/{payment}',
        [PaymentController::class, 'update']
)->middleware('permission:payments.update');

    Route::delete(
        '/payments/{payment}',
        [PaymentController::class, 'destroy']
)->middleware('permission:payments.delete');

    Route::post(
        '/payments/{payment}/complete',
        [PaymentController::class, 'complete']
)->middleware('permission:payments.complete');

    Route::post(
        '/payments/{payment}/refund',
        [PaymentController::class, 'refund']
)->middleware('permission:payments.refund');

    Route::post(
        '/payments/{payment}/cancel',
        [PaymentController::class, 'cancel']
)->middleware('permission:payments.cancel');
});
