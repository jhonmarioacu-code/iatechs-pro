<?php

declare(strict_types=1);

namespace App\Domains\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Tenant\Traits\BelongsToCompany;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public const PENDING = 'PENDING';

    public const COMPLETED = 'COMPLETED';

    public const FAILED = 'FAILED';

    public const REFUNDED = 'REFUNDED';

    public const CANCELLED = 'CANCELLED';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'uuid',

        'company_id',
        'branch_id',

        'invoice_id',
        'customer_id',

        'processed_by',

        'payment_number',

        'payment_method',

        'external_transaction_id',

        'reference',

        'currency',

        'amount',

        'is_partial',

        'status',

        'paid_at',

        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'amount' => 'decimal:2',

        'is_partial' => 'boolean',

        'paid_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Invoices\Models\Invoice::class
        );
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Customers\Models\Customer::class
        );
    }

    public function processedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class,
            'processed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::FAILED;
    }

    public function isRefunded(): bool
    {
        return $this->status === self::REFUNDED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::CANCELLED;
    }
}
