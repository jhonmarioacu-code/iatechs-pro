<?php

declare(strict_types=1);

namespace App\Domains\Compliance\Services;

use App\Domains\Compliance\Enums\ComplianceRecordStatus;
use App\Domains\Compliance\Models\ComplianceRecord;
use App\Domains\Compliance\Repositories\ComplianceRecordRepository;
use Illuminate\Support\Str;

class ComplianceRecordService
{
    public function __construct(
        private ComplianceRecordRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): ComplianceRecord
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? ComplianceRecordStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(ComplianceRecord $complianceRecord, array $data): ComplianceRecord
    {
        return $this->repository->update($complianceRecord, $data);
    }

    public function delete(ComplianceRecord $complianceRecord): bool
    {
        return $this->repository->delete($complianceRecord);
    }
}
