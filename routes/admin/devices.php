<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Devices\Controllers\DeviceController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/devices',
        [DeviceController::class, 'index']
    );

    Route::post(
        '/devices',
        [DeviceController::class, 'store']
    );

    Route::get(
        '/devices/{device}',
        [DeviceController::class, 'show']
    );

    Route::put(
        '/devices/{device}',
        [DeviceController::class, 'update']
    );

    Route::delete(
        '/devices/{device}',
        [DeviceController::class, 'destroy']
    );

    Route::post(
        '/devices/{device}/activate',
        [DeviceController::class, 'activate']
    );

    Route::post(
        '/devices/{device}/deactivate',
        [DeviceController::class, 'deactivate']
    );
});