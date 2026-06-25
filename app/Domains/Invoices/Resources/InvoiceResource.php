<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            'uuid' => $this->uuid,

            'invoice_series' => $this->invoice_series,

            'invoice_number' => $this->invoice_number,

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

            'customer_id' => $this->customer_id,

            'billing_id' => $this->billing_id,

            'ticket_id' => $this->ticket_id,

            'repair_id' => $this->repair_id,

            /*
            |--------------------------------------------------------------------------
            | Financial
            |--------------------------------------------------------------------------
            */

            'status' => $this->status,

            'subtotal' => $this->subtotal,

            'tax' => $this->tax,

            'discount' => $this->discount,

            'total' => $this->total,

            'currency' => $this->currency,

            'exchange_rate' => $this->exchange_rate,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'issued_at' => $this->issued_at,

            'due_date' => $this->due_date,

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

            'customer' => $this->whenLoaded(
                'customer'
            ),

            'billing' => $this->whenLoaded(
                'billing'
            ),

            'ticket' => $this->whenLoaded(
                'ticket'
            ),

            'repair' => $this->whenLoaded(
                'repair'
            ),

            'items' => $this->whenLoaded(
                'items'
            ),

            'payments' => $this->whenLoaded(
                'payments'
            ),

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

            'deleted_at' => $this->deleted_at,
        ];
    }
}