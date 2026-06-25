<?php

declare(strict_types=1);

namespace App\Domains\AuditLogs\Services;

use Illuminate\Support\Str;
use App\Domains\AuditLogs\Models\AuditLog;
use App\Domains\AuditLogs\Repositories\AuditLogRepository;

class AuditLogService
{
    public function __construct(
        private AuditLogRepository $repository
    ) {}

    public function paginate()
    {
        return $this->repository
            ->paginate();
    }

    public function log(
        string $event,
        string $module,
        string $entityType,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): AuditLog {

        return $this->repository->create([

            'uuid' =>
                (string) Str::uuid(),

            'company_id' =>
                auth()->user()?->company_id,

            'user_id' =>
                auth()->id(),

            'event' =>
                $event,

            'entity_type' =>
                $entityType,

            'entity_id' =>
                $entityId,

            'old_values' =>
                $oldValues,

            'new_values' =>
                $newValues,

            'ip_address' =>
                request()->ip(),

            'user_agent' =>
                request()->userAgent(),

            'url' =>
                request()->fullUrl(),

            'method' =>
                request()->method(),

            'module' =>
                $module,

            'occurred_at' =>
                now(),
        ]);
    }

    public function created(
        string $module,
        string $entityType,
        int $entityId,
        array $newValues = []
    ): AuditLog {

        return $this->log(
            'created',
            $module,
            $entityType,
            $entityId,
            null,
            $newValues
        );
    }

    public function updated(
        string $module,
        string $entityType,
        int $entityId,
        array $oldValues = [],
        array $newValues = []
    ): AuditLog {

        return $this->log(
            'updated',
            $module,
            $entityType,
            $entityId,
            $oldValues,
            $newValues
        );
    }

    public function deleted(
        string $module,
        string $entityType,
        int $entityId
    ): AuditLog {

        return $this->log(
            'deleted',
            $module,
            $entityType,
            $entityId
        );
    }

    public function custom(
        string $event,
        string $module,
        string $entityType,
        int $entityId
    ): AuditLog {

        return $this->log(
            $event,
            $module,
            $entityType,
            $entityId
        );
    }
}