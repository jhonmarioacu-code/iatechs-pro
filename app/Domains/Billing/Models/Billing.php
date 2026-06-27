<?php

declare(strict_types=1);

namespace App\Domains\Billing\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Billing extends Model
{
    use HasFactory;
    use BelongsToCompany;
    use SoftDeletes;

    protected $table = 'billings';

    protected $fillable = [

        'uuid',

        'company_id',
        'subscription_id',

        'reference',

        'amount',
        'currency',

        'billing_date',
        'due_date',

        'status',

        'payment_provider',
        'external_payment_id',

        'notes',
    ];

    protected $casts = [

        'amount' => 'decimal:2',

        'billing_date' => 'date',

        'due_date' => 'date',
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

    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Subscriptions\Models\Subscription::class
        );
    }
}
