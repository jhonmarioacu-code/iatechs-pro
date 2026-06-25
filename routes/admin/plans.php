<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Plans\Controllers\PlanController;

Route::middleware([
    'auth',
    'role:super_admin'
])->group(function () {

    Route::get('/plans', [PlanController::class, 'index']);
    Route::post('/plans', [PlanController::class, 'store']);

    Route::get('/plans/{plan}', [PlanController::class, 'show']);

    Route::put('/plans/{plan}', [PlanController::class, 'update']);

    Route::delete('/plans/{plan}', [PlanController::class, 'destroy']);
});