<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Subscriptions\Models\Subscription;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubscriptionRepository
{
    use ProvidesRepositoryDefaults;

    /**
     * Paginated Subscriptions
     */
    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Subscription::query()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find by ID
     */
    public function find(
        int $id
    ): ?Subscription {

        return Subscription::find($id);
    }

    /**
     * Find by UUID
     */
    public function findByUuid(
        string $uuid
    ): ?Subscription {

        return Subscription::where(
            'uuid',
            $uuid
        )->first();
    }

    /**
     * Active Subscriptions
     */
    public function active()
    {
        return Subscription::where(
            'status',
            'active'
        )->get();
    }

    /**
     * Company Subscriptions
     */
    public function byCompany(
        int $companyId
    )
    {
        return Subscription::where(
            'company_id',
            $companyId
        )->get();
    }

    /**
     * Create Subscription
     */
    public function create(
        array $data
    ): Subscription {

        return Subscription::create($data);
    }

    /**
     * Update Subscription
     */
    public function update(
        Subscription $subscription,
        array $data
    ): Subscription {

        $subscription->update(
            $data
        );

        return $subscription->refresh();
    }

    /**
     * Delete Subscription
     */
    public function delete(
        Subscription $subscription
    ): bool {

        return (bool) $subscription->delete();
    }
}