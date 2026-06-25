<?php

declare(strict_types=1);

use App\Domains\SystemSettings\Controllers\SystemSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('system-settings')
    ->name('system-settings.')
    ->group(function () {
        Route::get('/', [SystemSettingController::class, 'index'])
            ->middleware('permission:system-settings.view')
            ->name('index');

        Route::post('/', [SystemSettingController::class, 'store'])
            ->middleware('permission:system-settings.create')
            ->name('store');

        Route::get('/{systemSetting}', [SystemSettingController::class, 'show'])
            ->middleware('permission:system-settings.view')
            ->name('show');

        Route::put('/{systemSetting}', [SystemSettingController::class, 'update'])
            ->middleware('permission:system-settings.update')
            ->name('update');

        Route::delete('/{systemSetting}', [SystemSettingController::class, 'destroy'])
            ->middleware('permission:system-settings.delete')
            ->name('destroy');
    });
