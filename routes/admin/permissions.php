<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\RolesPermissions\Controllers\PermissionController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
    'role:super_admin',
])->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])
        ->middleware('permission:permissions.view');
    Route::post('/permissions', [PermissionController::class, 'store'])
        ->middleware('permission:permissions.create');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])
        ->middleware('permission:permissions.view');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])
        ->middleware('permission:permissions.update');
    Route::patch('/permissions/{permission}', [PermissionController::class, 'update'])
        ->middleware('permission:permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])
        ->middleware('permission:permissions.delete');
});
