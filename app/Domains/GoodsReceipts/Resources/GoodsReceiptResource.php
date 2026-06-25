<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodsReceiptResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'receipt_number' => $this->receipt_number,

            'company_id' => $this->company_id,

            'purchase_order_id' => $this->purchase_order_id,

            'supplier_id' => $this->supplier_id,

            'received_by' => $this->received_by,

            'status' => $this->status,

            'subtotal' => $this->subtotal,

            'tax' => $this->tax,

            'discount' => $this->discount,

            'total' => $this->total,

            'notes' => $this->notes,

            'received_at' => $this->received_at,

            'supplier' => $this->whenLoaded('supplier'),

            'purchase_order' => $this->whenLoaded('purchaseOrder'),

            'items' => $this->whenLoaded('items'),

            'created_at' => $this->created_at,
        ];
    }
}