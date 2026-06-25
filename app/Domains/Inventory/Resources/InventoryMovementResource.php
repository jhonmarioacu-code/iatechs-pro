<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryMovementResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            'product_id' => $this->product_id,

            'user_id' => $this->user_id,

            'reference' => $this->reference,

            'type' => $this->type,

            'quantity' => $this->quantity,

            'stock_before' => $this->stock_before,

            'stock_after' => $this->stock_after,

            'reason' => $this->reason,

            'metadata' => $this->metadata,

            'movement_date' => $this->movement_date,

            'product' => $this->whenLoaded(
                'product'
            ),

            'branch' => $this->whenLoaded(
                'branch'
            ),

            'user' => $this->whenLoaded(
                'user'
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}