<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Companies\Controllers\CompanyController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/companies',
        [CompanyController::class, 'index']
    )->middleware('permission:companies.view');

    Route::post(
        '/companies',
        [CompanyController::class, 'store']
    )->middleware('permission:companies.create');

    Route::get(
        '/companies/{company}',
        [CompanyController::class, 'show']
    )->middleware('permission:companies.view');

    Route::put(
        '/companies/{company}',
        [CompanyController::class, 'update']
    )->middleware('permission:companies.update');

    Route::delete(
        '/companies/{company}',
        [CompanyController::class, 'destroy']
    )->middleware('permission:companies.delete');
});
