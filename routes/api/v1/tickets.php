<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Tickets\Controllers\TicketController;

Route::middleware([
    'auth:sanctum',
    'tenant',
])
->prefix('tickets')
->group(function () {
    Route::get('/', [TicketController::class, 'index']);
    Route::post('/', [TicketController::class, 'store']);
    Route::get('/{ticket}', [TicketController::class, 'show']);
    Route::put('/{ticket}', [TicketController::class, 'update']);
    Route::delete('/{ticket}', [TicketController::class, 'destroy']);
    Route::post('/{ticket}/assign', [TicketController::class, 'assign']);
    Route::post('/{ticket}/close', [TicketController::class, 'close']);
    Route::post('/{ticket}/cancel', [TicketController::class, 'cancel']);
    Route::post('/{ticket}/status', [TicketController::class, 'changeStatus']);
});

