<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsReceiptItem extends Model
{
    use HasFactory;

    protected $fillable = [

        'goods_receipt_id',

        'purchase_order_item_id',

        'product_id',

        'ordered_quantity',

        'received_quantity',

        'pending_quantity',

        'unit_cost',

        'subtotal',

        'notes',
    ];

    protected $casts = [

        'unit_cost' => 'decimal:2',

        'subtotal' => 'decimal:2',
    ];

    public function goodsReceipt(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            GoodsReceipt::class
        );
    }

    public function purchaseOrderItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\PurchaseOrders\Models\PurchaseOrderItem::class
        );
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Products\Models\Product::class
        );
    }
}