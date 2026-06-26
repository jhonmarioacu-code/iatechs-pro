<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockTransfer extends Model
{
    use HasFactory;
    use BelongsToCompany;
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'company_id',

        'product_id',

        'from_branch_id',
        'to_branch_id',

        'requested_by',
        'approved_by',

        'transfer_number',

        'quantity',

        'status',

        'notes',

        'requested_at',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [

        'requested_at' => 'datetime',

        'approved_at' => 'datetime',

        'completed_at' => 'datetime',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Products\Models\Product::class
        );
    }

    public function fromBranch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Branches\Models\Branch::class,
            'from_branch_id'
        );
    }

    public function toBranch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Branches\Models\Branch::class,
            'to_branch_id'
        );
    }

    public function requester(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class,
            'requested_by'
        );
    }

    public function approver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class,
            'approved_by'
        );
    }
}
