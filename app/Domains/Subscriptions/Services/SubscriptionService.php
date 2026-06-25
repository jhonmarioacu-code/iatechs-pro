<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Domains\Plans\Models\Plan;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Subscriptions\Repositories\SubscriptionRepository;

class SubscriptionService
{
    public function __construct(
        protected SubscriptionRepository $repository
    ) {}

    /**
     * Paginated subscriptions
     */
    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    /**
     * Create subscription
     */
    public function create(
        array $data
    ): Subscription {

        $data['uuid'] = (string) Str::uuid();

        $data['status'] = $data['status']
            ?? 'active';

        return $this->repository
            ->create($data);
    }

    /**
     * Update subscription
     */
    public function update(
        Subscription $subscription,
        array $data
    ): Subscription {

        return $this->repository
            ->update(
                $subscription,
                $data
            );
    }

    /**
     * Delete subscription
     */
    public function delete(
        Subscription $subscription
    ): bool {

        return $this->repository
            ->delete($subscription);
    }

    /**
     * Activate subscription
     */
    public function activate(
        Subscription $subscription
    ): Subscription {

        return $this->repository
            ->update(
                $subscription,
                [
                    'status' => 'active'
                ]
            );
    }

    /**
     * Cancel subscription
     */
    public function cancel(
        Subscription $subscription
    ): Subscription {

        return $this->repository
            ->update(
                $subscription,
                [
                    'status' => 'cancelled',
                    'cancelled_at' => now()
                ]
            );
    }

    /**
     * Change plan
     */
    public function changePlan(
        Subscription $subscription,
        Plan $plan
    ): Subscription {

        return $this->repository
            ->update(
                $subscription,
                [
                    'plan_id' => $plan->id,
                    'amount' => $plan->monthly_price
                ]
            );
    }

    /**
     * Renew subscription
     */
    public function renew(
        Subscription $subscription
    ): Subscription {

        $endsAt = $subscription->billing_cycle === 'yearly'
            ? Carbon::today()->addYear()
            : Carbon::today()->addMonth();

        return $this->repository
            ->update(
                $subscription,
                [
                    'starts_at' => Carbon::today(),
                    'ends_at' => $endsAt,
                    'status' => 'active'
                ]
            );
    }
}