<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Users\Controllers\UserController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/users',
        [UserController::class, 'index']
    )->middleware('permission:users.view');

    Route::post(
        '/users',
        [UserController::class, 'store']
    )->middleware('permission:users.create');

    Route::get(
        '/users/{user}',
        [UserController::class, 'show']
    )->middleware('permission:users.view');

    Route::put(
        '/users/{user}',
        [UserController::class, 'update']
    )->middleware('permission:users.update');

    Route::delete(
        '/users/{user}',
        [UserController::class, 'destroy']
    )->middleware('permission:users.delete');
});
