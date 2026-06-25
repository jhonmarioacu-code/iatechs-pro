<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Analytics\Models\Analytic;

class AnalyticRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return Analytic::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Analytic
    {
        return Analytic::create($data);
    }

    public function update(Analytic $analytic, array $data): Analytic
    {
        $analytic->update($data);

        return $analytic->refresh();
    }

    public function delete(Analytic $analytic): bool
    {
        return (bool) $analytic->delete();
    }
}
