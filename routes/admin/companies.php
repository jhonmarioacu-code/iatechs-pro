<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Companies\Controllers\CompanyController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/companies',
        [CompanyController::class, 'index']
    );

    Route::post(
        '/companies',
        [CompanyController::class, 'store']
    );

    Route::get(
        '/companies/{company}',
        [CompanyController::class, 'show']
    );

    Route::put(
        '/companies/{company}',
        [CompanyController::class, 'update']
    );

    Route::delete(
        '/companies/{company}',
        [CompanyController::class, 'destroy']
    );
});