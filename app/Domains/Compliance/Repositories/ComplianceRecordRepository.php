<?php

declare(strict_types=1);

namespace App\Domains\Compliance\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Compliance\Models\ComplianceRecord;

class ComplianceRecordRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return ComplianceRecord::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): ComplianceRecord
    {
        return ComplianceRecord::create($data);
    }

    public function update(ComplianceRecord $complianceRecord, array $data): ComplianceRecord
    {
        $complianceRecord->update($data);

        return $complianceRecord->refresh();
    }

    public function delete(ComplianceRecord $complianceRecord): bool
    {
        return (bool) $complianceRecord->delete();
    }
}
