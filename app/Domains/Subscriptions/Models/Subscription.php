<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;
    use BelongsToCompany;
    use SoftDeletes;

    protected $table = 'subscriptions';

    protected $fillable = [

        'uuid',

        'company_id',
        'plan_id',

        'billing_cycle',

        'amount',

        'starts_at',
        'ends_at',

        'trial_ends_at',

        'status',

        'payment_provider',
        'external_subscription_id',

        'cancelled_at',
    ];

    protected $casts = [

        'amount' => 'decimal:2',

        'starts_at' => 'date',
        'ends_at' => 'date',

        'trial_ends_at' => 'datetime',

        'cancelled_at' => 'datetime',
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

    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Plans\Models\Plan::class
        );
    }
}
