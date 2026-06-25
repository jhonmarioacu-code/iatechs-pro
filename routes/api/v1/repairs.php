<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Repairs\Controllers\RepairController;

Route::middleware([
    'auth:sanctum',
    'tenant',
])
->prefix('repairs')
->group(function () {
    Route::get('/', [RepairController::class, 'index']);
    Route::post('/', [RepairController::class, 'store']);
    Route::get('/{repair}', [RepairController::class, 'show']);
    Route::put('/{repair}', [RepairController::class, 'update']);
    Route::delete('/{repair}', [RepairController::class, 'destroy']);
    Route::post('/{repair}/assign', [RepairController::class, 'assign']);
    Route::post('/{repair}/start', [RepairController::class, 'start']);
    Route::post('/{repair}/complete', [RepairController::class, 'complete']);
    Route::post('/{repair}/deliver', [RepairController::class, 'deliver']);
    Route::post('/{repair}/cancel', [RepairController::class, 'cancel']);
});

