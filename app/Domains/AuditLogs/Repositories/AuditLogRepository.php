<?php

declare(strict_types=1);

namespace App\Domains\AuditLogs\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\AuditLogs\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuditLogRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 50
    ): LengthAwarePaginator {

        return AuditLog::query()
            ->with([
                'company',
                'user'
            ])
            ->latest('occurred_at')
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): AuditLog {

        return AuditLog::create(
            $data
        );
    }

    public function find(
        int $id
    ): ?AuditLog {

        return AuditLog::with([
            'company',
            'user'
        ])->find($id);
    }

    public function byModule(
        string $module,
        int $perPage = 50
    ): LengthAwarePaginator {

        return AuditLog::query()
            ->where(
                'module',
                $module
            )
            ->latest('occurred_at')
            ->paginate($perPage);
    }

    public function byUser(
        int $userId,
        int $perPage = 50
    ): LengthAwarePaginator {

        return AuditLog::query()
            ->where(
                'user_id',
                $userId
            )
            ->latest('occurred_at')
            ->paginate($perPage);
    }
}