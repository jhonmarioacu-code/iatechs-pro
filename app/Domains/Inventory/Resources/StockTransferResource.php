<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockTransferResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'transfer_number' => $this->transfer_number,

            'company_id' => $this->company_id,

            'product_id' => $this->product_id,

            'from_branch_id' => $this->from_branch_id,

            'to_branch_id' => $this->to_branch_id,

            'requested_by' => $this->requested_by,

            'approved_by' => $this->approved_by,

            'quantity' => $this->quantity,

            'status' => $this->status,

            'notes' => $this->notes,

            'requested_at' => $this->requested_at,

            'approved_at' => $this->approved_at,

            'completed_at' => $this->completed_at,

            'product' => $this->whenLoaded('product'),

            'from_branch' => $this->whenLoaded('fromBranch'),

            'to_branch' => $this->whenLoaded('toBranch'),

            'created_at' => $this->created_at,
        ];
    }
}