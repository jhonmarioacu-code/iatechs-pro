<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\RolesPermissions\Controllers\RoleController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
    'role:super_admin',
])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:roles.view');
    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:roles.create');
    Route::get('/roles/{role}', [RoleController::class, 'show'])
        ->middleware('permission:roles.view');
    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:roles.update');
    Route::patch('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:roles.delete');
});
