<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'repair_number' => $this->repair_number,

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            'ticket_id' => $this->ticket_id,

            'diagnostic_id' => $this->diagnostic_id,

            'quote_id' => $this->quote_id,

            'technician_id' => $this->technician_id,

            'status' => $this->status,

            'repair_notes' => $this->repair_notes,

            'labor_cost' => $this->labor_cost,

            'parts_cost' => $this->parts_cost,

            'total_cost' => $this->total_cost,

            'started_at' => $this->started_at,

            'completed_at' => $this->completed_at,

            'delivered_at' => $this->delivered_at,

            'company' => $this->whenLoaded(
                'company'
            ),

            'branch' => $this->whenLoaded(
                'branch'
            ),

            'ticket' => $this->whenLoaded(
                'ticket'
            ),

            'diagnostic' => $this->whenLoaded(
                'diagnostic'
            ),

            'quote' => $this->whenLoaded(
                'quote'
            ),

            'technician' => $this->whenLoaded(
                'technician'
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}