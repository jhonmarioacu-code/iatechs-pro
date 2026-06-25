<?php

declare(strict_types=1);

use App\Domains\TechnicianSchedules\Controllers\TechnicianScheduleController;
use Illuminate\Support\Facades\Route;

Route::prefix('technician-schedules')
    ->name('technician-schedules.')
    ->group(function () {
        Route::get('/', [TechnicianScheduleController::class, 'index'])
            ->middleware('permission:technician-schedules.view')
            ->name('index');

        Route::post('/', [TechnicianScheduleController::class, 'store'])
            ->middleware('permission:technician-schedules.create')
            ->name('store');

        Route::get('/{technicianSchedule}', [TechnicianScheduleController::class, 'show'])
            ->middleware('permission:technician-schedules.view')
            ->name('show');

        Route::put('/{technicianSchedule}', [TechnicianScheduleController::class, 'update'])
            ->middleware('permission:technician-schedules.update')
            ->name('update');

        Route::delete('/{technicianSchedule}', [TechnicianScheduleController::class, 'destroy'])
            ->middleware('permission:technician-schedules.delete')
            ->name('destroy');
    });
