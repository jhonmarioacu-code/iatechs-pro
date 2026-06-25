<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\Plans\Controllers\PlanController;

/*
|--------------------------------------------------------------------------
| Plans
|--------------------------------------------------------------------------
*/

Route::middleware([
    'tenant'
])

->prefix('plans')

->name('plans.')

->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [PlanController::class, 'index']
    )
    ->middleware('permission:plans.view')
    ->name('index');

    Route::post(
        '/',
        [PlanController::class, 'store']
    )
    ->middleware('permission:plans.create')
    ->name('store');

    Route::get(
        '/{plan}',
        [PlanController::class, 'show']
    )
    ->middleware('permission:plans.view')
    ->name('show');

    Route::put(
        '/{plan}',
        [PlanController::class, 'update']
    )
    ->middleware('permission:plans.update')
    ->name('update');

    Route::delete(
        '/{plan}',
        [PlanController::class, 'destroy']
    )
    ->middleware('permission:plans.delete')
    ->name('destroy');

    /*
    |--------------------------------------------------------------------------
    | Status Actions
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/{plan}/activate',
        [PlanController::class, 'activate']
    )
    ->middleware('permission:plans.activate')
    ->name('activate');

    Route::patch(
        '/{plan}/deactivate',
        [PlanController::class, 'deactivate']
    )
    ->middleware('permission:plans.deactivate')
    ->name('deactivate');
});