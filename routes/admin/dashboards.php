<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Dashboard\Controllers\DashboardController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/dashboards',
        [DashboardController::class, 'index']
    );

    Route::get(
        '/dashboards/{dashboard}',
        [DashboardController::class, 'show']
    );
});
