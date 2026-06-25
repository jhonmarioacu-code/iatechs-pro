<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\RolesPermissions\Controllers\RoleController;

Route::middleware([
    'auth',
    'role:super_admin',
])->group(function () {
    Route::resource('roles', RoleController::class);
});
