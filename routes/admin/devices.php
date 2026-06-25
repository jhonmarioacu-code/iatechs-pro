<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Devices\Controllers\DeviceController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/devices',
        [DeviceController::class, 'index']
)->middleware('permission:devices.view');

    Route::post(
        '/devices',
        [DeviceController::class, 'store']
)->middleware('permission:devices.create');

    Route::get(
        '/devices/{device}',
        [DeviceController::class, 'show']
)->middleware('permission:devices.view');

    Route::put(
        '/devices/{device}',
        [DeviceController::class, 'update']
)->middleware('permission:devices.update');

    Route::delete(
        '/devices/{device}',
        [DeviceController::class, 'destroy']
)->middleware('permission:devices.delete');

    Route::post(
        '/devices/{device}/activate',
        [DeviceController::class, 'activate']
)->middleware('permission:devices.update');

    Route::post(
        '/devices/{device}/deactivate',
        [DeviceController::class, 'deactivate']
)->middleware('permission:devices.update');
});
