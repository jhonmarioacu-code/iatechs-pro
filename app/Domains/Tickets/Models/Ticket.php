<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Tenant\Traits\BelongsToCompany;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $fillable = [

        'uuid',

        'company_id',
        'branch_id',

        'customer_id',
        'device_id',

        'technician_id',

        'ticket_number',

        'status',
        'priority',
        'channel',

        'reported_problem',
        'customer_notes',

        'received_at',
        'assigned_at',
        'closed_at',

        'is_warranty'
    ];

    protected $casts = [

        'received_at' => 'datetime',
        'assigned_at' => 'datetime',
        'closed_at'   => 'datetime',

        'is_warranty' => 'boolean'
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

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Customers\Models\Customer::class
        );
    }

    public function device(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Devices\Models\Device::class
        );
    }

    public function technician(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class,
            'technician_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Future Relationships
    |--------------------------------------------------------------------------
    */

    public function diagnostics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Diagnostics\Models\Diagnostic::class
        );
    }

    public function quotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Quotes\Models\Quote::class
        );
    }

    public function repairs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Repairs\Models\Repair::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsOpenAttribute(): bool
    {
        return in_array(
            $this->status,
            [
                'OPEN',
                'ASSIGNED',
                'IN_DIAGNOSIS',
                'WAITING_QUOTE',
                'APPROVED',
                'IN_REPAIR',
                'READY_DELIVERY'
            ]
        );
    }

    public function getIsClosedAttribute(): bool
    {
        return in_array(
            $this->status,
            [
                'DELIVERED',
                'CLOSED',
                'CANCELLED'
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeOpen($query)
    {
        return $query->whereIn(
            'status',
            [
                'OPEN',
                'ASSIGNED',
                'IN_DIAGNOSIS',
                'WAITING_QUOTE',
                'APPROVED',
                'IN_REPAIR',
                'READY_DELIVERY'
            ]
        );
    }

    public function scopeClosed($query)
    {
        return $query->whereIn(
            'status',
            [
                'DELIVERED',
                'CLOSED',
                'CANCELLED'
            ]
        );
    }

    public function scopeByCompany(
        $query,
        int $companyId
    ) {
        return $query->where(
            'company_id',
            $companyId
        );
    }

    public function scopeByBranch(
        $query,
        int $branchId
    ) {
        return $query->where(
            'branch_id',
            $branchId
        );
    }
}
