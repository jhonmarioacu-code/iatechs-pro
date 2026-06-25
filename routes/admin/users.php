<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Users\Controllers\UserController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/users',
        [UserController::class, 'index']
    );

    Route::post(
        '/users',
        [UserController::class, 'store']
    );

    Route::get(
        '/users/{user}',
        [UserController::class, 'show']
    );

    Route::put(
        '/users/{user}',
        [UserController::class, 'update']
    );

    Route::delete(
        '/users/{user}',
        [UserController::class, 'destroy']
    );
});