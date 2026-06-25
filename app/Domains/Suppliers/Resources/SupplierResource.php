<?php

declare(strict_types=1);

namespace App\Domains\Suppliers\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'name' => $this->name,

            'legal_name' => $this->legal_name,

            'tax_id' => $this->tax_id,

            'email' => $this->email,

            'phone' => $this->phone,

            'website' => $this->website,

            'contact_name' => $this->contact_name,

            'address' => $this->address,

            'city' => $this->city,

            'state' => $this->state,

            'country' => $this->country,

            'status' => $this->status,

            'metadata' => $this->metadata,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}