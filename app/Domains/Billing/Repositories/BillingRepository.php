<?php

declare(strict_types=1);

namespace App\Domains\Billing\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Billing\Models\Billing;

class BillingRepository
{
    use ProvidesRepositoryDefaults;

    /**
     * Paginated List
     */
    public function paginate(
        int $perPage = 20
    )
    {
        return Billing::query()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find By ID
     */
    public function find(
        int $id
    ): ?Billing {

        return Billing::find($id);
    }

    /**
     * Find By UUID
     */
    public function findByUuid(
        string $uuid
    ): ?Billing {

        return Billing::where(
            'uuid',
            $uuid
        )->first();
    }

    /**
     * Create Billing
     */
    public function create(
        array $data
    ): Billing {

        return Billing::create($data);
    }

    /**
     * Update Billing
     */
    public function update(
        Billing $billing,
        array $data
    ): Billing {

        $billing->update($data);

        return $billing->refresh();
    }

    /**
     * Delete Billing
     */
    public function delete(
        Billing $billing
    ): bool {

        return (bool) $billing->delete();
    }

    /**
     * By Company
     */
    public function byCompany(
        int $companyId
    )
    {
        return Billing::query()
            ->where(
                'company_id',
                $companyId
            )
            ->latest()
            ->paginate(20);
    }

    /**
     * By Subscription
     */
    public function bySubscription(
        int $subscriptionId
    )
    {
        return Billing::query()
            ->where(
                'subscription_id',
                $subscriptionId
            )
            ->latest()
            ->paginate(20);
    }

    /**
     * Pending Billings
     */
    public function pending()
    {
        return Billing::query()
            ->where(
                'status',
                'pending'
            )
            ->latest()
            ->paginate(20);
    }

    /**
     * Paid Billings
     */
    public function paid()
    {
        return Billing::query()
            ->where(
                'status',
                'paid'
            )
            ->latest()
            ->paginate(20);
    }
}