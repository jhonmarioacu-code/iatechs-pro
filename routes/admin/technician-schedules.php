<?php

declare(strict_types=1);

use App\Domains\TechnicianSchedules\Controllers\TechnicianScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('technician-schedules')
    ->name('technician-schedules.')
    ->group(function () {
        Route::get('/', [TechnicianScheduleController::class, 'index'])->name('index');
        Route::post('/', [TechnicianScheduleController::class, 'store'])->name('store');
        Route::get('/{technicianSchedule}', [TechnicianScheduleController::class, 'show'])->name('show');
        Route::put('/{technicianSchedule}', [TechnicianScheduleController::class, 'update'])->name('update');
        Route::delete('/{technicianSchedule}', [TechnicianScheduleController::class, 'destroy'])->name('destroy');
    });
