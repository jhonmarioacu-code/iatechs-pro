<?php

declare(strict_types=1);

namespace App\Domains\AuditLogs\Policies;

use App\Domains\Users\Models\User;
use App\Domains\AuditLogs\Models\AuditLog;

class AuditLogPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'audit_logs.view'
        );
    }

    public function view(
        User $user,
        AuditLog $auditLog
    ): bool {

        return $user->can(
            'audit_logs.view'
        );
    }
}