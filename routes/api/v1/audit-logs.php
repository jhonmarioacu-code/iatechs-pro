<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\AuditLogs\Controllers\AuditLogController;

Route::prefix('audit-logs')
    ->name('audit-logs.')
    ->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])
            ->middleware('permission:auditlogs.view')
            ->name('index');

        Route::get('/{auditLog}', [AuditLogController::class, 'show'])
            ->middleware('permission:auditlogs.view')
            ->name('show');
    });

