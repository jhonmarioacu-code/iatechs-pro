<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;
    use BelongsToCompany;
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'company_id',

        'supplier_id',

        'created_by',

        'order_number',

        'status',

        'subtotal',
        'tax',
        'discount',
        'total',

        'notes',

        'ordered_at',
        'approved_at',
        'received_at',
    ];

    protected $casts = [

        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',

        'ordered_at' => 'datetime',
        'approved_at' => 'datetime',
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

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class,
            'created_by'
        );
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            PurchaseOrderItem::class
        );
    }
}
