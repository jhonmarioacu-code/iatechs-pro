<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Diagnostics\Controllers\DiagnosticController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/diagnostics',
        [DiagnosticController::class, 'index']
    )->middleware('permission:diagnostics.view');

    Route::post(
        '/diagnostics',
        [DiagnosticController::class, 'store']
    )->middleware('permission:diagnostics.create');

    Route::get(
        '/diagnostics/{diagnostic}',
        [DiagnosticController::class, 'show']
    )->middleware('permission:diagnostics.view');

    Route::put(
        '/diagnostics/{diagnostic}',
        [DiagnosticController::class, 'update']
    )->middleware('permission:diagnostics.update');

    Route::delete(
        '/diagnostics/{diagnostic}',
        [DiagnosticController::class, 'destroy']
    )->middleware('permission:diagnostics.delete');

    Route::post(
        '/diagnostics/{diagnostic}/start',
        [DiagnosticController::class, 'start']
    )->middleware('permission:diagnostics.start');

    Route::post(
        '/diagnostics/{diagnostic}/complete',
        [DiagnosticController::class, 'complete']
    )->middleware('permission:diagnostics.complete');

    Route::post(
        '/diagnostics/{diagnostic}/cancel',
        [DiagnosticController::class, 'cancel']
    )->middleware('permission:diagnostics.cancel');
});
