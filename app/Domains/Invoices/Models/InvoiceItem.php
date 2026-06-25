<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Tenant\Traits\BelongsToCompany;

class InvoiceItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $table = 'invoice_items';

    /*
    |--------------------------------------------------------------------------
    | Types
    |--------------------------------------------------------------------------
    */

    public const TYPE_PRODUCT = 'product';

    public const TYPE_SERVICE = 'service';

    public const TYPE_REPAIR = 'repair';

    public const TYPE_PART = 'part';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'company_id',

        'invoice_id',

        'product_id',

        'type',

        'name',

        'description',

        'quantity',

        'unit_price',

        'discount',

        'tax',

        'total',

        'sort_order',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'quantity' => 'decimal:2',

        'unit_price' => 'decimal:2',

        'discount' => 'decimal:2',

        'tax' => 'decimal:2',

        'total' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Invoice::class
        );
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Products\Models\Product::class
        );
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function calculateTotal(): float
    {
        return (
            ($this->quantity * $this->unit_price)
            + $this->tax
        ) - $this->discount;
    }

    public function isProduct(): bool
    {
        return $this->type === self::TYPE_PRODUCT;
    }

    public function isService(): bool
    {
        return $this->type === self::TYPE_SERVICE;
    }

    public function isRepair(): bool
    {
        return $this->type === self::TYPE_REPAIR;
    }

    public function isPart(): bool
    {
        return $this->type === self::TYPE_PART;
    }
}
