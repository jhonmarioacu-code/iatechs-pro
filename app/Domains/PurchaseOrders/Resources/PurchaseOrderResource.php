<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'order_number' => $this->order_number,

            'company_id' => $this->company_id,

            'supplier_id' => $this->supplier_id,

            'created_by' => $this->created_by,

            'status' => $this->status,

            'subtotal' => $this->subtotal,

            'tax' => $this->tax,

            'discount' => $this->discount,

            'total' => $this->total,

            'notes' => $this->notes,

            'ordered_at' => $this->ordered_at,

            'approved_at' => $this->approved_at,

            'received_at' => $this->received_at,

            'supplier' => $this->whenLoaded(
                'supplier'
            ),

            'items' => $this->whenLoaded(
                'items'
            ),

            'created_at' => $this->created_at,
        ];
    }
}