<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'company_id' => $this->company_id,

            'company' => $this->whenLoaded(
                'company',
                fn () => [
                    'id' => $this->company->id,
                    'name' => $this->company->name,
                ]
            ),

            'plan_id' => $this->plan_id,

            'plan' => $this->whenLoaded(
                'plan',
                fn () => [
                    'id' => $this->plan->id,
                    'name' => $this->plan->name,
                    'slug' => $this->plan->slug,
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | Subscription
            |--------------------------------------------------------------------------
            */

            'amount' => $this->amount,

            'billing_cycle' => $this->billing_cycle,

            'status' => $this->status,

            'starts_at' => $this->starts_at,

            'ends_at' => $this->ends_at,

            'trial_ends_at' => $this->trial_ends_at,

            /*
            |--------------------------------------------------------------------------
            | Provider
            |--------------------------------------------------------------------------
            */

            'payment_provider' => $this->payment_provider,

            'external_subscription_id' => $this->external_subscription_id,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'cancelled_at' => $this->cancelled_at,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}