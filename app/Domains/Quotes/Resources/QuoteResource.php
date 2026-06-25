<?php

declare(strict_types=1);

namespace App\Domains\Quotes\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'quote_number' => $this->quote_number,

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            'ticket_id' => $this->ticket_id,

            'diagnostic_id' => $this->diagnostic_id,

            'status' => $this->status,

            'subtotal' => $this->subtotal,

            'tax' => $this->tax,

            'discount' => $this->discount,

            'total' => $this->total,

            'notes' => $this->notes,

            'approved_at' => $this->approved_at,

            'rejected_at' => $this->rejected_at,

            'expires_at' => $this->expires_at,

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

            'items' => $this->whenLoaded(
                'items'
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}