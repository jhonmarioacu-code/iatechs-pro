<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Repairs\Controllers\RepairController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/repairs',
        [RepairController::class, 'index']
    );

    Route::post(
        '/repairs',
        [RepairController::class, 'store']
    );

    Route::get(
        '/repairs/{repair}',
        [RepairController::class, 'show']
    );

    Route::put(
        '/repairs/{repair}',
        [RepairController::class, 'update']
    );

    Route::delete(
        '/repairs/{repair}',
        [RepairController::class, 'destroy']
    );

    Route::post(
        '/repairs/{repair}/assign',
        [RepairController::class, 'assign']
    );

    Route::post(
        '/repairs/{repair}/start',
        [RepairController::class, 'start']
    );

    Route::post(
        '/repairs/{repair}/complete',
        [RepairController::class, 'complete']
    );

    Route::post(
        '/repairs/{repair}/deliver',
        [RepairController::class, 'deliver']
    );

    Route::post(
        '/repairs/{repair}/cancel',
        [RepairController::class, 'cancel']
    );
});