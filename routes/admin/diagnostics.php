<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Diagnostics\Controllers\DiagnosticController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/diagnostics',
        [DiagnosticController::class, 'index']
    );

    Route::post(
        '/diagnostics',
        [DiagnosticController::class, 'store']
    );

    Route::get(
        '/diagnostics/{diagnostic}',
        [DiagnosticController::class, 'show']
    );

    Route::put(
        '/diagnostics/{diagnostic}',
        [DiagnosticController::class, 'update']
    );

    Route::delete(
        '/diagnostics/{diagnostic}',
        [DiagnosticController::class, 'destroy']
    );

    Route::post(
        '/diagnostics/{diagnostic}/start',
        [DiagnosticController::class, 'start']
    );

    Route::post(
        '/diagnostics/{diagnostic}/complete',
        [DiagnosticController::class, 'complete']
    );

    Route::post(
        '/diagnostics/{diagnostic}/cancel',
        [DiagnosticController::class, 'cancel']
    );
});