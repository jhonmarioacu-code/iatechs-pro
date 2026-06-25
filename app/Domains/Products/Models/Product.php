<?php

declare(strict_types=1);

namespace App\Domains\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Tenant\Traits\BelongsToCompany;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $fillable = [

        'uuid',

        'company_id',

        'sku',
        'barcode',

        'name',
        'description',

        'category',

        'cost_price',
        'sale_price',

        'stock',
        'minimum_stock',

        'unit',

        'status',
    ];

    protected $casts = [

        'cost_price' => 'decimal:2',

        'sale_price' => 'decimal:2',

        'stock' => 'integer',

        'minimum_stock' => 'integer',
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }
}
