<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Notifications\Controllers\NotificationController;

Route::prefix('notifications')
    ->name('notifications.')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'index'])
            ->middleware('permission:notifications.view')
            ->name('index');

        Route::post('/', [NotificationController::class, 'store'])
            ->middleware('permission:notifications.create')
            ->name('store');

        Route::get('/{notification}', [NotificationController::class, 'show'])
            ->middleware('permission:notifications.view')
            ->name('show');

        Route::put('/{notification}', [NotificationController::class, 'update'])
            ->middleware('permission:notifications.update')
            ->name('update');

        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])
            ->middleware('permission:notifications.read')
            ->name('read');
    });

