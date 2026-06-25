<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Branches\Controllers\BranchController;

/*
|--------------------------------------------------------------------------
| Branches API v1
|--------------------------------------------------------------------------
*/

Route::prefix('branches')

    ->name('branches.')

    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CRUD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [BranchController::class, 'index']
        )->name('index');

        Route::post(
            '/',
            [BranchController::class, 'store']
        )->name('store');

        Route::get(
            '/{branch}',
            [BranchController::class, 'show']
        )->name('show');

        Route::put(
            '/{branch}',
            [BranchController::class, 'update']
        )->name('update');

        Route::delete(
            '/{branch}',
            [BranchController::class, 'destroy']
        )->name('destroy');

        /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{branch}/activate',
            [BranchController::class, 'activate']
        )->name('activate');

        Route::patch(
            '/{branch}/deactivate',
            [BranchController::class, 'deactivate']
        )->name('deactivate');
    });