<?php

declare(strict_types=1);

namespace App\Domains\Products\Resources;

use App\Domains\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'sku' => $this->sku,

            'barcode' => $this->barcode,

            'name' => $this->name,

            'description' => $this->description,

            'category' => $this->category,

            'cost_price' => $this->cost_price,

            'sale_price' => $this->sale_price,

            'stock' => $this->stock,

            'minimum_stock' => $this->minimum_stock,

            'unit' => $this->unit,

            'status' => $this->status,

            'low_stock' => $this->resource instanceof Product
                ? $this->resource->isLowStock()
                : false,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}
