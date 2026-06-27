<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryMovement extends Model
{
    use HasFactory;
    use BelongsToCompany;
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'company_id',
        'branch_id',

        'product_id',
        'user_id',

        'reference',

        'type',

        'quantity',

        'stock_before',
        'stock_after',

        'reason',

        'metadata',

        'movement_date',
    ];

    protected $casts = [

        'metadata' => 'array',

        'movement_date' => 'datetime',
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Branches\Models\Branch::class
        );
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Products\Models\Product::class
        );
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class
        );
    }
}
