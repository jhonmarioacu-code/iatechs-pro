<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Users\Controllers\UserController;

Route::middleware([
    'auth:sanctum',
    'tenant',
])
->prefix('users')
->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
});

