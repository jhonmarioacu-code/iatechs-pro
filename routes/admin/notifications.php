<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Notifications\Controllers\NotificationController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    );

    Route::post(
        '/notifications',
        [NotificationController::class, 'store']
    );

    Route::get(
        '/notifications/{notification}',
        [NotificationController::class, 'show']
    );

    Route::put(
        '/notifications/{notification}',
        [NotificationController::class, 'update']
    );

    Route::post(
        '/notifications/{notification}/read',
        [NotificationController::class, 'markAsRead']
    );
});