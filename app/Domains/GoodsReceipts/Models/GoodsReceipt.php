<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsReceipt extends Model
{
    use HasFactory;
    use BelongsToCompany;
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'company_id',

        'purchase_order_id',

        'supplier_id',

        'received_by',

        'receipt_number',

        'status',

        'subtotal',
        'tax',
        'discount',
        'total',

        'notes',

        'received_at',
    ];

    protected $casts = [

        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',

        'received_at' => 'datetime',
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Suppliers\Models\Supplier::class
        );
    }

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\PurchaseOrders\Models\PurchaseOrder::class
        );
    }

    public function receiver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class,
            'received_by'
        );
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            GoodsReceiptItem::class
        );
    }
}
