<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\AuditLogs\Controllers\AuditLogController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/audit-logs',
        [AuditLogController::class, 'index']
    );

    Route::get(
        '/audit-logs/{auditLog}',
        [AuditLogController::class, 'show']
    );
});