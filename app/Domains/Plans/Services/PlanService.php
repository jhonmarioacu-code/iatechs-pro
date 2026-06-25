<?php

declare(strict_types=1);

namespace App\Domains\Plans\Services;

use Illuminate\Support\Str;

use App\Domains\Plans\Models\Plan;
use App\Domains\Plans\Repositories\PlanRepository;

class PlanService
{
    public function __construct(
        protected PlanRepository $repository
    ) {}

    /**
     * List Plans
     */
    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    /**
     * Create Plan
     */
    public function create(
        array $data
    ): Plan {

        $data['uuid'] = (string) Str::uuid();

        $data['slug'] = Str::slug(
            $data['name']
        );

        $data['status'] = $data['status']
            ?? 'active';

        return $this->repository
            ->create($data);
    }

    /**
     * Update Plan
     */
    public function update(
        Plan $plan,
        array $data
    ): Plan {

        if (isset($data['name'])) {

            $data['slug'] = Str::slug(
                $data['name']
            );
        }

        return $this->repository
            ->update(
                $plan,
                $data
            );
    }

    /**
     * Delete Plan
     */
    public function delete(
        Plan $plan
    ): bool {

        return $this->repository
            ->delete($plan);
    }

    /**
     * Activate Plan
     */
    public function activate(
        Plan $plan
    ): Plan {

        return $this->repository
            ->update(
                $plan,
                [
                    'status' => 'active'
                ]
            );
    }

    /**
     * Deactivate Plan
     */
    public function deactivate(
        Plan $plan
    ): Plan {

        return $this->repository
            ->update(
                $plan,
                [
                    'status' => 'inactive'
                ]
            );
    }
}