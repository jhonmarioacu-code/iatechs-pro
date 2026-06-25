<?php

declare(strict_types=1);

namespace App\Domains\BusinessIntelligence\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\BusinessIntelligence\Models\BusinessMetric;

class BusinessMetricRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return BusinessMetric::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): BusinessMetric
    {
        return BusinessMetric::create($data);
    }

    public function update(BusinessMetric $businessMetric, array $data): BusinessMetric
    {
        $businessMetric->update($data);

        return $businessMetric->refresh();
    }

    public function delete(BusinessMetric $businessMetric): bool
    {
        return (bool) $businessMetric->delete();
    }
}
