<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;

use App\Domains\Plans\Models\Plan;

use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Subscriptions\Services\SubscriptionService;

use App\Domains\Subscriptions\Requests\StoreSubscriptionRequest;
use App\Domains\Subscriptions\Requests\UpdateSubscriptionRequest;

use App\Domains\Subscriptions\Resources\SubscriptionResource;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $service
    ) {}

    /**
     * List Subscriptions
     */
    public function index()
    {
        $this->authorize('viewAny', Subscription::class);

        return SubscriptionResource::collection(
            $this->service->paginate()
        );
    }

    /**
     * Store Subscription
     */
    public function store(
        StoreSubscriptionRequest $request
    ): SubscriptionResource {
        $this->authorize('create', Subscription::class);

        return new SubscriptionResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    /**
     * Show Subscription
     */
    public function show(
        Subscription $subscription
    ): SubscriptionResource {
        $this->authorize('view', $subscription);

        return new SubscriptionResource(
            $subscription->load([
                'company',
                'plan'
            ])
        );
    }

    /**
     * Update Subscription
     */
    public function update(
        UpdateSubscriptionRequest $request,
        Subscription $subscription
    ): SubscriptionResource {
        $this->authorize('update', $subscription);

        return new SubscriptionResource(
            $this->service->update(
                $subscription,
                $request->validated()
            )
        );
    }

    /**
     * Delete Subscription
     */
    public function destroy(
        Subscription $subscription
    ): JsonResponse {
        $this->authorize('delete', $subscription);

        $this->service->delete(
            $subscription
        );

        return response()->json([
            'success' => true,
            'message' => 'Subscription deleted successfully'
        ]);
    }

    /**
     * Activate Subscription
     */
    public function activate(
        Subscription $subscription
    ): SubscriptionResource {
        $this->authorize('activate', $subscription);

        return new SubscriptionResource(
            $this->service->activate(
                $subscription
            )
        );
    }

    /**
     * Cancel Subscription
     */
    public function cancel(
        Subscription $subscription
    ): SubscriptionResource {
        $this->authorize('cancel', $subscription);

        return new SubscriptionResource(
            $this->service->cancel(
                $subscription
            )
        );
    }

    /**
     * Renew Subscription
     */
    public function renew(
        Subscription $subscription
    ): SubscriptionResource {
        $this->authorize('renew', $subscription);

        return new SubscriptionResource(
            $this->service->renew(
                $subscription
            )
        );
    }

    /**
     * Change Plan
     */
    public function changePlan(
        Request $request,
        Subscription $subscription
    ): SubscriptionResource {
        $this->authorize('changePlan', $subscription);

        $request->validate([
            'plan_id' => [
                'required',
                'exists:plans,id'
            ]
        ]);

        $plan = Plan::findOrFail(
            $request->plan_id
        );

        return new SubscriptionResource(
            $this->service->changePlan(
                $subscription,
                $plan
            )
        );
    }
}
