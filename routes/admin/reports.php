<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Reports\Controllers\ReportController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/reports',
        [ReportController::class, 'index']
)->middleware('permission:reports.view');

    Route::post(
        '/reports',
        [ReportController::class, 'store']
)->middleware('permission:reports.export');

    Route::get(
        '/reports/{report}',
        [ReportController::class, 'show']
)->middleware('permission:reports.view');
});
