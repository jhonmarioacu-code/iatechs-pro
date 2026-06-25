<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Dashboard\Models\Dashboard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DashboardRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Dashboard::query()
            ->with('widgets')
            ->latest()
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Dashboard {

        return Dashboard::create(
            $data
        );
    }

    public function update(
        Dashboard $dashboard,
        array $data
    ): Dashboard {

        $dashboard->update($data);

        return $dashboard->refresh();
    }

    public function find(
        int $id
    ): ?Dashboard {

        return Dashboard::with(
            'widgets'
        )->find($id);
    }
}
