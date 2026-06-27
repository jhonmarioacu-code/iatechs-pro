<?php

declare(strict_types=1);

namespace App\Domains\Suppliers\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    use BelongsToCompany;
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'company_id',

        'name',
        'legal_name',

        'tax_id',

        'email',
        'phone',

        'website',

        'contact_name',

        'address',
        'city',
        'state',
        'country',

        'status',

        'metadata',
    ];

    protected $casts = [

        'metadata' => 'array',
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    public function purchaseOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\PurchaseOrders\Models\PurchaseOrder::class
        );
    }

    public function goodsReceipts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\GoodsReceipts\Models\GoodsReceipt::class
        );
    }
}
