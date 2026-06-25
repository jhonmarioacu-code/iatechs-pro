<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\RolesPermissions\Controllers\PermissionController;

Route::middleware([
    'auth',
    'role:super_admin',
])->group(function () {
    Route::resource('permissions', PermissionController::class);
});
