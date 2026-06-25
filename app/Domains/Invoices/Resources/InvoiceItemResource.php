<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'company_id' => $this->company_id,

            'invoice_id' => $this->invoice_id,

            'product_id' => $this->product_id,

            'type' => $this->type,

            'name' => $this->name,

            'description' => $this->description,

            'quantity' => $this->quantity,

            'unit_price' => $this->unit_price,

            'discount' => $this->discount,

            'tax' => $this->tax,

            'total' => $this->total,

            'sort_order' => $this->sort_order,

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'product' => $this->whenLoaded(
                'product'
            ),

            'invoice' => $this->whenLoaded(
                'invoice'
            ),

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

            'deleted_at' => $this->deleted_at,
        ];
    }
}