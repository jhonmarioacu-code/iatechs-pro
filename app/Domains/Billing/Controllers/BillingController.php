<?php

declare(strict_types=1);

namespace App\Domains\Billing\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Billing\Models\Billing;
use App\Domains\Billing\Services\BillingService;

use App\Domains\Billing\Requests\StoreBillingRequest;
use App\Domains\Billing\Requests\UpdateBillingRequest;

use App\Domains\Billing\Resources\BillingResource;

class BillingController extends Controller
{
    public function __construct(
        protected BillingService $service
    ) {}

    /**
     * List Billings
     */
    public function index()
    {
        return BillingResource::collection(
            $this->service->paginate()
        );
    }

    /**
     * Create Billing
     */
    public function store(
        StoreBillingRequest $request
    )
    {
        return new BillingResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    /**
     * Show Billing
     */
    public function show(
        Billing $billing
    )
    {
        $billing->load([
            'company',
            'subscription'
        ]);

        return new BillingResource(
            $billing
        );
    }

    /**
     * Update Billing
     */
    public function update(
        UpdateBillingRequest $request,
        Billing $billing
    )
    {
        return new BillingResource(
            $this->service->update(
                $billing,
                $request->validated()
            )
        );
    }

    /**
     * Delete Billing
     */
    public function destroy(
        Billing $billing
    )
    {
        $this->service->delete(
            $billing
        );

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Mark Paid
     */
    public function markPaid(
        Billing $billing
    )
    {
        return new BillingResource(
            $this->service->markAsPaid(
                $billing
            )
        );
    }

    /**
     * Mark Failed
     */
    public function markFailed(
        Billing $billing
    )
    {
        return new BillingResource(
            $this->service->markAsFailed(
                $billing
            )
        );
    }

    /**
     * Cancel Billing
     */
    public function cancel(
        Billing $billing
    )
    {
        return new BillingResource(
            $this->service->cancel(
                $billing
            )
        );
    }

    /**
     * Refund Billing
     */
    public function refund(
        Billing $billing
    )
    {
        return new BillingResource(
            $this->service->update(
                $billing,
                [
                    'status' => 'refunded'
                ]
            )
        );
    }
}