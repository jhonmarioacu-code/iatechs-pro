<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Reports\Controllers\ReportController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/reports',
        [ReportController::class, 'index']
    );

    Route::post(
        '/reports',
        [ReportController::class, 'store']
    );

    Route::get(
        '/reports/{report}',
        [ReportController::class, 'show']
    );
});