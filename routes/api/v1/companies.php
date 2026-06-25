<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Companies\Controllers\CompanyController;

/*
|--------------------------------------------------------------------------
| Companies API v1
|--------------------------------------------------------------------------
*/

Route::prefix('companies')

    ->name('companies.')

    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CRUD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [CompanyController::class, 'index']
        )
        ->middleware('permission:companies.view')
        ->name('index');

        Route::post(
            '/',
            [CompanyController::class, 'store']
        )
        ->middleware('permission:companies.create')
        ->name('store');

        Route::get(
            '/{company}',
            [CompanyController::class, 'show']
        )
        ->middleware('permission:companies.view')
        ->name('show');

        Route::put(
            '/{company}',
            [CompanyController::class, 'update']
        )
        ->middleware('permission:companies.update')
        ->name('update');

        Route::delete(
            '/{company}',
            [CompanyController::class, 'destroy']
        )
        ->middleware('permission:companies.delete')
        ->name('destroy');

        /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{company}/activate',
            [CompanyController::class, 'activate']
        )
        ->middleware('permission:companies.activate')
        ->name('activate');

        Route::patch(
            '/{company}/suspend',
            [CompanyController::class, 'suspend']
        )
        ->middleware('permission:companies.suspend')
        ->name('suspend');

        Route::patch(
            '/{company}/cancel',
            [CompanyController::class, 'cancel']
        )
        ->middleware('permission:companies.delete')
        ->name('cancel');
    });