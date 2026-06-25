<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Repairs\Controllers\RepairController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/repairs',
        [RepairController::class, 'index']
    )->middleware('permission:repairs.view');

    Route::post(
        '/repairs',
        [RepairController::class, 'store']
    )->middleware('permission:repairs.create');

    Route::get(
        '/repairs/{repair}',
        [RepairController::class, 'show']
    )->middleware('permission:repairs.view');

    Route::put(
        '/repairs/{repair}',
        [RepairController::class, 'update']
    )->middleware('permission:repairs.update');

    Route::delete(
        '/repairs/{repair}',
        [RepairController::class, 'destroy']
    )->middleware('permission:repairs.delete');

    Route::post(
        '/repairs/{repair}/assign',
        [RepairController::class, 'assign']
    )->middleware('permission:repairs.assign');

    Route::post(
        '/repairs/{repair}/start',
        [RepairController::class, 'start']
    )->middleware('permission:repairs.start');

    Route::post(
        '/repairs/{repair}/complete',
        [RepairController::class, 'complete']
    )->middleware('permission:repairs.complete');

    Route::post(
        '/repairs/{repair}/deliver',
        [RepairController::class, 'deliver']
    )->middleware('permission:repairs.deliver');

    Route::post(
        '/repairs/{repair}/cancel',
        [RepairController::class, 'cancel']
    )->middleware('permission:repairs.cancel');
});
