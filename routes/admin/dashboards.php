<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Dashboard\Controllers\DashboardController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/dashboards',
        [DashboardController::class, 'index']
)->middleware('permission:reports.view');

    Route::get(
        '/dashboards/{dashboard}',
        [DashboardController::class, 'show']
)->middleware('permission:reports.view');
});

