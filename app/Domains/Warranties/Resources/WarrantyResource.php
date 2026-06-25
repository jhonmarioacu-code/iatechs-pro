<?php

declare(strict_types=1);

namespace App\Domains\Warranties\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'warranty_number' => $this->warranty_number,

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            'customer_id' => $this->customer_id,

            'device_id' => $this->device_id,

            'repair_id' => $this->repair_id,

            'invoice_id' => $this->invoice_id,

            'type' => $this->type,

            'status' => $this->status,

            'start_date' => $this->start_date,

            'end_date' => $this->end_date,

            'terms' => $this->terms,

            'notes' => $this->notes,

            'customer' => $this->whenLoaded(
                'customer'
            ),

            'device' => $this->whenLoaded(
                'device'
            ),

            'repair' => $this->whenLoaded(
                'repair'
            ),

            'invoice' => $this->whenLoaded(
                'invoice'
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}