<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Tickets\Controllers\TicketController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/tickets',
        [TicketController::class, 'index']
    )->middleware('permission:tickets.view');

    Route::post(
        '/tickets',
        [TicketController::class, 'store']
    )->middleware('permission:tickets.create');

    Route::get(
        '/tickets/{ticket}',
        [TicketController::class, 'show']
    )->middleware('permission:tickets.view');

    Route::put(
        '/tickets/{ticket}',
        [TicketController::class, 'update']
    )->middleware('permission:tickets.update');

    Route::delete(
        '/tickets/{ticket}',
        [TicketController::class, 'destroy']
    )->middleware('permission:tickets.delete');

    Route::post(
        '/tickets/{ticket}/assign',
        [TicketController::class, 'assign']
    )->middleware('permission:tickets.assign');

    Route::post(
        '/tickets/{ticket}/close',
        [TicketController::class, 'close']
    )->middleware('permission:tickets.close');

    Route::post(
        '/tickets/{ticket}/cancel',
        [TicketController::class, 'cancel']
    )->middleware('permission:tickets.cancel');

    Route::post(
        '/tickets/{ticket}/status',
        [TicketController::class, 'changeStatus']
    )->middleware('permission:tickets.update');
});
