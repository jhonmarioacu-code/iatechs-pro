<?php

declare(strict_types=1);

namespace App\Domains\Branches\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Tenant\Traits\BelongsToCompany;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $table = 'branches';

    protected $fillable = [

        'company_id',

        'uuid',

        'name',
        'slug',
        'code',

        'phone',
        'email',

        'manager_name',

        'address',
        'city',
        'state',
        'country',

        'timezone',

        'is_main',
        'is_active',

        'metadata'
    ];

    protected $casts = [

        'is_main' => 'boolean',

        'is_active' => 'boolean',

        'metadata' => 'array',
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

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Users\Models\User::class
        );
    }

    public function customers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Customers\Models\Customer::class
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

    public function isMain(): bool
    {
        return $this->is_main;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}