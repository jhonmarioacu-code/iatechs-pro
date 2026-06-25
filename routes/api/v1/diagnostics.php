<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Diagnostics\Controllers\DiagnosticController;

Route::middleware([
    'auth:sanctum',
    'tenant',
])
->prefix('diagnostics')
->group(function () {
    Route::get('/', [DiagnosticController::class, 'index']);
    Route::post('/', [DiagnosticController::class, 'store']);
    Route::get('/{diagnostic}', [DiagnosticController::class, 'show']);
    Route::put('/{diagnostic}', [DiagnosticController::class, 'update']);
    Route::delete('/{diagnostic}', [DiagnosticController::class, 'destroy']);
    Route::post('/{diagnostic}/start', [DiagnosticController::class, 'start']);
    Route::post('/{diagnostic}/complete', [DiagnosticController::class, 'complete']);
    Route::post('/{diagnostic}/cancel', [DiagnosticController::class, 'cancel']);
});
