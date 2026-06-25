<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Tickets\Controllers\TicketController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/tickets',
        [TicketController::class, 'index']
    );

    Route::post(
        '/tickets',
        [TicketController::class, 'store']
    );

    Route::get(
        '/tickets/{ticket}',
        [TicketController::class, 'show']
    );

    Route::put(
        '/tickets/{ticket}',
        [TicketController::class, 'update']
    );

    Route::delete(
        '/tickets/{ticket}',
        [TicketController::class, 'destroy']
    );

    Route::post(
        '/tickets/{ticket}/assign',
        [TicketController::class, 'assign']
    );

    Route::post(
        '/tickets/{ticket}/close',
        [TicketController::class, 'close']
    );

    Route::post(
        '/tickets/{ticket}/cancel',
        [TicketController::class, 'cancel']
    );

    Route::post(
        '/tickets/{ticket}/status',
        [TicketController::class, 'changeStatus']
    );
});