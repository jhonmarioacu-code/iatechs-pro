<?php

declare(strict_types=1);

use App\Domains\SystemSettings\Controllers\SystemSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('system-settings')
    ->name('system-settings.')
    ->group(function () {
        Route::get('/', [SystemSettingController::class, 'index'])->name('index');
        Route::post('/', [SystemSettingController::class, 'store'])->name('store');
        Route::get('/{systemSetting}', [SystemSettingController::class, 'show'])->name('show');
        Route::put('/{systemSetting}', [SystemSettingController::class, 'update'])->name('update');
        Route::delete('/{systemSetting}', [SystemSettingController::class, 'destroy'])->name('destroy');
    });
