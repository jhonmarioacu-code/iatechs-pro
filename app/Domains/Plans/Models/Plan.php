<?php

declare(strict_types=1);

namespace App\Domains\Plans\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'plans';

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'uuid',

        'name',
        'slug',
        'description',

        'monthly_price',
        'yearly_price',

        'max_users',
        'max_branches',
        'max_storage_gb',
        'max_tickets',

        'ai_requests_limit',

        'trial_days',

        'has_ai',
        'has_inventory',
        'has_reports',

        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',

        'max_users' => 'integer',
        'max_branches' => 'integer',
        'max_storage_gb' => 'integer',
        'max_tickets' => 'integer',

        'ai_requests_limit' => 'integer',

        'trial_days' => 'integer',

        'has_ai' => 'boolean',
        'has_inventory' => 'boolean',
        'has_reports' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Subscriptions\Models\Subscription::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function hasAI(): bool
    {
        return $this->has_ai;
    }

    public function hasInventory(): bool
    {
        return $this->has_inventory;
    }

    public function hasReports(): bool
    {
        return $this->has_reports;
    }
}