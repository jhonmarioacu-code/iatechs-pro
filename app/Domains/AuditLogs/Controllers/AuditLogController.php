<?php

declare(strict_types=1);

namespace App\Domains\AuditLogs\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\AuditLogs\Models\AuditLog;
use App\Domains\AuditLogs\Repositories\AuditLogRepository;
use App\Domains\AuditLogs\Requests\FilterAuditLogRequest;
use App\Domains\AuditLogs\Resources\AuditLogResource;

class AuditLogController extends Controller
{
    public function __construct(
        private AuditLogRepository $repository
    ) {}

    public function index(
        FilterAuditLogRequest $request
    )
    {
        return AuditLogResource::collection(
            $this->repository->paginate(
                $request->integer(
                    'per_page',
                    50
                )
            )
        );
    }

    public function show(
        AuditLog $auditLog
    )
    {
        return new AuditLogResource(
            $auditLog->load([
                'company',
                'user'
            ])
        );
    }
}