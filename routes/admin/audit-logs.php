<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\AuditLogs\Controllers\AuditLogController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/audit-logs',
        [AuditLogController::class, 'index']
)->middleware('permission:auditlogs.view');

    Route::get(
        '/audit-logs/{auditLog}',
        [AuditLogController::class, 'show']
)->middleware('permission:auditlogs.view');
});
