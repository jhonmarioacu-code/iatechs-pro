<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'ticket_number' => $this->ticket_number,

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            'customer_id' => $this->customer_id,

            'device_id' => $this->device_id,

            'technician_id' => $this->technician_id,

            'status' => $this->status,

            'priority' => $this->priority,

            'channel' => $this->channel,

            'reported_problem' => $this->reported_problem,

            'customer_notes' => $this->customer_notes,

            'received_at' => $this->received_at,

            'assigned_at' => $this->assigned_at,

            'closed_at' => $this->closed_at,

            'is_warranty' => $this->is_warranty,

            'company' => $this->whenLoaded(
                'company'
            ),

            'branch' => $this->whenLoaded(
                'branch'
            ),

            'customer' => $this->whenLoaded(
                'customer'
            ),

            'device' => $this->whenLoaded(
                'device'
            ),

            'technician' => $this->whenLoaded(
                'technician'
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}