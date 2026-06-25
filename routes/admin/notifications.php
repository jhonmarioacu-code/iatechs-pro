<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Notifications\Controllers\NotificationController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
)->middleware('permission:notifications.view');

    Route::post(
        '/notifications',
        [NotificationController::class, 'store']
)->middleware('permission:notifications.create');

    Route::get(
        '/notifications/{notification}',
        [NotificationController::class, 'show']
)->middleware('permission:notifications.view');

    Route::put(
        '/notifications/{notification}',
        [NotificationController::class, 'update']
)->middleware('permission:notifications.update');

    Route::post(
        '/notifications/{notification}/read',
        [NotificationController::class, 'markAsRead']
)->middleware('permission:notifications.read');
});
