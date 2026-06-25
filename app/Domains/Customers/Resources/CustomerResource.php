<?php

declare(strict_types=1);

namespace App\Domains\Customers\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'customer_code' => $this->customer_code,

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            'customer_type' => $this->customer_type,

            'first_name' => $this->first_name,

            'last_name' => $this->last_name,

            'full_name' => $this->full_name,

            'company_name' => $this->company_name,

            'document_type' => $this->document_type,

            'document_number' => $this->document_number,

            'email' => $this->email,

            'phone' => $this->phone,

            'mobile' => $this->mobile,

            'address' => $this->address,

            'city' => $this->city,

            'state' => $this->state,

            'country' => $this->country,

            'credit_limit' => $this->credit_limit,

            'notes' => $this->notes,

            'accepts_marketing' => $this->accepts_marketing,

            'is_active' => $this->is_active,

            'company' => $this->whenLoaded(
                'company'
            ),

            'branch' => $this->whenLoaded(
                'branch'
            ),

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),

            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}