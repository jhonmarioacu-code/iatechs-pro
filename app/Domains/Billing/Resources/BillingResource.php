<?php

declare(strict_types=1);

namespace App\Domains\Billing\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Identifiers
            |--------------------------------------------------------------------------
            */

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

                    'slug' => $this->company->slug,
                ]
            ),

            'subscription_id' => $this->subscription_id,

            'subscription' => $this->whenLoaded(
                'subscription',
                fn () => [

                    'id' => $this->subscription->id,

                    'uuid' => $this->subscription->uuid,

                    'status' => $this->subscription->status,

                    'billing_cycle' => $this->subscription->billing_cycle,
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | Billing
            |--------------------------------------------------------------------------
            */

            'reference' => $this->reference,

            'amount' => $this->amount,

            'currency' => $this->currency,

            'billing_date' => $this->billing_date,

            'due_date' => $this->due_date,

            'status' => $this->status,

            /*
            |--------------------------------------------------------------------------
            | Payment Gateway
            |--------------------------------------------------------------------------
            */

            'payment_provider' => $this->payment_provider,

            'external_payment_id' => $this->external_payment_id,

            /*
            |--------------------------------------------------------------------------
            | Extra
            |--------------------------------------------------------------------------
            */

            'notes' => $this->notes,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

            'deleted_at' => $this->deleted_at,
        ];
    }
}