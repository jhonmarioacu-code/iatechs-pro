<?php

declare(strict_types=1);

namespace App\Domains\Plans\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Plans\Models\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PlanRepository
{
    use ProvidesRepositoryDefaults;

    /**
     * Paginated Plans
     */
    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Plan::query()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find by ID
     */
    public function find(
        int $id
    ): ?Plan {

        return Plan::find($id);
    }

    /**
     * Find by UUID
     */
    public function findByUuid(
        string $uuid
    ): ?Plan {

        return Plan::where(
            'uuid',
            $uuid
        )->first();
    }

    /**
     * Find by Slug
     */
    public function findBySlug(
        string $slug
    ): ?Plan {

        return Plan::where(
            'slug',
            $slug
        )->first();
    }

    /**
     * Active Plans
     */
    public function active()
    {
        return Plan::where(
            'status',
            'active'
        )->get();
    }

    /**
     * Create Plan
     */
    public function create(
        array $data
    ): Plan {

        return Plan::create($data);
    }

    /**
     * Update Plan
     */
    public function update(
        Plan $plan,
        array $data
    ): Plan {

        $plan->update($data);

        return $plan->refresh();
    }

    /**
     * Delete Plan
     */
    public function delete(
        Plan $plan
    ): bool {

        return (bool) $plan->delete();
    }
}