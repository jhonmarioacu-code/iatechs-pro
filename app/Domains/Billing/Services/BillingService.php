<?php

declare(strict_types=1);

namespace App\Domains\Billing\Services;

use Illuminate\Support\Str;

use App\Domains\Billing\Models\Billing;
use App\Domains\Billing\Repositories\BillingRepository;

use App\Domains\Subscriptions\Models\Subscription;

class BillingService
{
    public function __construct(
        protected BillingRepository $repository
    ) {}

    /**
     * Paginated Billing
     */
    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    /**
     * Create Billing
     */
    public function create(
        array $data
    ): Billing {

        $data['uuid'] = (string) Str::uuid();

        $data['reference'] = $data['reference']
            ?? $this->generateReference();

        $data['status'] = $data['status']
            ?? 'pending';

        return $this->repository
            ->create($data);
    }

    /**
     * Update Billing
     */
    public function update(
        Billing $billing,
        array $data
    ): Billing {

        return $this->repository
            ->update(
                $billing,
                $data
            );
    }

    /**
     * Delete Billing
     */
    public function delete(
        Billing $billing
    ): bool {

        return $this->repository
            ->delete($billing);
    }

    /**
     * Mark As Paid
     */
    public function markAsPaid(
        Billing $billing
    ): Billing {

        return $this->repository
            ->update(
                $billing,
                [
                    'status' => 'paid'
                ]
            );
    }

    /**
     * Mark As Failed
     */
    public function markAsFailed(
        Billing $billing
    ): Billing {

        return $this->repository
            ->update(
                $billing,
                [
                    'status' => 'failed'
                ]
            );
    }

    /**
     * Cancel Billing
     */
    public function cancel(
        Billing $billing
    ): Billing {

        return $this->repository
            ->update(
                $billing,
                [
                    'status' => 'cancelled'
                ]
            );
    }

    /**
     * Create Billing From Subscription
     */
    public function createFromSubscription(
        Subscription $subscription
    ): Billing {

        return $this->repository
            ->create([

                'uuid' => (string) Str::uuid(),

                'company_id' => $subscription->company_id,

                'subscription_id' => $subscription->id,

                'reference' => $this->generateReference(),

                'amount' => $subscription->amount,

                'currency' => 'USD',

                'billing_date' => now(),

                'due_date' => now()->addDays(7),

                'status' => 'pending',
            ]);
    }

    /**
     * Generate Reference
     */
    protected function generateReference(): string
    {
        return 'BILL-' .
            now()->format('YmdHis') .
            '-' .
            strtoupper(
                Str::random(6)
            );
    }
}