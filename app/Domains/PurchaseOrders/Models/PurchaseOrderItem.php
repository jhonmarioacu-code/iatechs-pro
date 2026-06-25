<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [

        'purchase_order_id',

        'product_id',

        'quantity',

        'unit_cost',

        'subtotal',
    ];

    protected $casts = [

        'unit_cost' => 'decimal:2',

        'subtotal' => 'decimal:2',
    ];

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            PurchaseOrder::class
        );
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Products\Models\Product::class
        );
    }
}