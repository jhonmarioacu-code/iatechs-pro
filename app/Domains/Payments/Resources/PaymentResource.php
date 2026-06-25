<?php

declare(strict_types=1);

namespace App\Domains\Payments\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            'uuid' => $this->uuid,

            'payment_number' => $this->payment_number,

            /*
            |--------------------------------------------------------------------------
            | Tenant
            |--------------------------------------------------------------------------
            */

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'invoice_id' => $this->invoice_id,

            'customer_id' => $this->customer_id,

            'processed_by' => $this->processed_by,

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment_method' => $this->payment_method,

            'external_transaction_id' =>
                $this->external_transaction_id,

            'reference' => $this->reference,

            'currency' => $this->currency,

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount' => $this->amount,

            'is_partial' => $this->is_partial,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => $this->status,

            'paid_at' => $this->paid_at,

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            'notes' => $this->notes,

            /*
            |--------------------------------------------------------------------------
            | Loaded Relations
            |--------------------------------------------------------------------------
            */

            'company' => $this->whenLoaded(
                'company'
            ),

            'branch' => $this->whenLoaded(
                'branch'
            ),

            'invoice' => $this->whenLoaded(
                'invoice'
            ),

            'customer' => $this->whenLoaded(
                'customer'
            ),

            'processed_by_user' =>
                $this->whenLoaded(
                    'processedBy'
                ),

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}