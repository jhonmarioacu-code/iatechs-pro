<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Plans\Controllers\PlanController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
    'role:super_admin'
])->group(function () {

    Route::get('/plans', [PlanController::class, 'index'])
        ->middleware('permission:plans.view');
    Route::post('/plans', [PlanController::class, 'store'])
        ->middleware('permission:plans.create');

    Route::get('/plans/{plan}', [PlanController::class, 'show'])
        ->middleware('permission:plans.view');

    Route::put('/plans/{plan}', [PlanController::class, 'update'])
        ->middleware('permission:plans.update');

    Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])
        ->middleware('permission:plans.delete');
});
