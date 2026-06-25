<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Services;

use Illuminate\Support\Str;

use App\Domains\Dashboard\Models\Dashboard;
use App\Domains\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        private DashboardRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    ) {
        return $this->repository
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Dashboard {

        return $this->repository->create([

            'uuid' => (string) Str::uuid(),

            'company_id' => $data['company_id'],

            'name' => $data['name'],

            'description' =>
                $data['description'] ?? null,

            'is_default' =>
                $data['is_default'] ?? false,
        ]);
    }

    public function update(
        Dashboard $dashboard,
        array $data
    ): Dashboard {

        return $this->repository->update(
            $dashboard,
            $data
        );
    }
}
