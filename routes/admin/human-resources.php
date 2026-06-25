<?php

declare(strict_types=1);

use App\Domains\HumanResources\Controllers\HumanResourceController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
    ->prefix('human-resources')
    ->name('human-resources.')
    ->group(function () {
        Route::get('/', [HumanResourceController::class, 'index'])->middleware('permission:human-resources.view')->name('index');
        Route::post('/', [HumanResourceController::class, 'store'])->middleware('permission:human-resources.create')->name('store');
        Route::get('/{humanResource}', [HumanResourceController::class, 'show'])->middleware('permission:human-resources.view')->name('show');
        Route::put('/{humanResource}', [HumanResourceController::class, 'update'])->middleware('permission:human-resources.update')->name('update');
        Route::delete('/{humanResource}', [HumanResourceController::class, 'destroy'])->middleware('permission:human-resources.delete')->name('destroy');
    });

