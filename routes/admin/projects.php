<?php

declare(strict_types=1);

use App\Domains\Projects\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
    ->prefix('projects')
    ->name('projects.')
    ->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->middleware('permission:projects.view')->name('index');
        Route::post('/', [ProjectController::class, 'store'])->middleware('permission:projects.create')->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])->middleware('permission:projects.view')->name('show');
        Route::put('/{project}', [ProjectController::class, 'update'])->middleware('permission:projects.update')->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->middleware('permission:projects.delete')->name('destroy');
    });

