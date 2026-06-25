<?php

declare(strict_types=1);

namespace App\Domains\BusinessIntelligence\Services;

use App\Domains\BusinessIntelligence\Enums\BusinessMetricStatus;
use App\Domains\BusinessIntelligence\Models\BusinessMetric;
use App\Domains\BusinessIntelligence\Repositories\BusinessMetricRepository;
use Illuminate\Support\Str;

class BusinessMetricService
{
    public function __construct(
        private BusinessMetricRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): BusinessMetric
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? BusinessMetricStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(BusinessMetric $businessMetric, array $data): BusinessMetric
    {
        return $this->repository->update($businessMetric, $data);
    }

    public function delete(BusinessMetric $businessMetric): bool
    {
        return $this->repository->delete($businessMetric);
    }
}
