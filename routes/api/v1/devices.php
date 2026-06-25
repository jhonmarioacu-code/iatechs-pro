<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Devices\Controllers\DeviceController;

Route::middleware([
    'auth:sanctum',
    'tenant',
])
->prefix('devices')
->group(function () {
    Route::get('/', [DeviceController::class, 'index']);
    Route::post('/', [DeviceController::class, 'store']);
    Route::get('/{device}', [DeviceController::class, 'show']);
    Route::put('/{device}', [DeviceController::class, 'update']);
    Route::delete('/{device}', [DeviceController::class, 'destroy']);
    Route::post('/{device}/activate', [DeviceController::class, 'activate']);
    Route::post('/{device}/deactivate', [DeviceController::class, 'deactivate']);
});

