<?php

declare(strict_types=1);

namespace App\Domains\Customers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Tenant\Traits\BelongsToCompany;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $table = 'customers';

    protected $fillable = [

        'company_id',
        'branch_id',

        'uuid',
        'customer_code',

        'customer_type',

        'first_name',
        'last_name',

        'company_name',

        'document_type',
        'document_number',

        'email',
        'phone',
        'mobile',

        'address',
        'city',
        'state',
        'country',

        'credit_limit',
        'balance',

        'customer_since',

        'accepts_marketing',

        'notes',

        'is_active',
    ];

    protected $casts = [

        'credit_limit' => 'decimal:2',

        'balance' => 'decimal:2',

        'customer_since' => 'date',

        'accepts_marketing' => 'boolean',

        'is_active' => 'boolean',
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

    public function devices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Devices\Models\Device::class
        );
    }

    public function tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Tickets\Models\Ticket::class
        );
    }

    public function repairs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Repairs\Models\Repair::class
        );
    }

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Invoices\Models\Invoice::class
        );
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Payments\Models\Payment::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPerson(): bool
    {
        return $this->customer_type === 'person';
    }

    public function isCompany(): bool
    {
        return $this->customer_type === 'company';
    }

    public function getFullNameAttribute(): string
    {
        return trim(
            ($this->first_name ?? '') .
            ' ' .
            ($this->last_name ?? '')
        );
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->isCompany()
            ? $this->company_name
            : $this->full_name;
    }
}