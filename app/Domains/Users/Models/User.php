<?php

declare(strict_types=1);

namespace App\Domains\Users\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;
    use HasRoles;

    protected string $guard_name = 'web';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'uuid',

        'company_id',
        'branch_id',

        'name',
        'email',
        'password',

        'phone',
        'avatar',

        'is_active',
        'technician_course_enabled',
        'technician_exam_enabled',

        'last_login_at',
        'email_verified_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [

        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'password' => 'hashed',

        'is_active' => 'boolean',
        'technician_course_enabled' => 'boolean',
        'technician_exam_enabled' => 'boolean',

        'last_login_at' => 'datetime',

        'email_verified_at' => 'datetime',
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

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(
            'super_admin'
        );
    }

    public function isCompanyOwner(): bool
    {
        return $this->hasRole(
            'owner'
        );
    }

    public function belongsToCompany(
        int $companyId
    ): bool {

        return $this->company_id === $companyId;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(
        $query
    ) {
        return $query->where(
            'is_active',
            true
        );
    }

    public function getMorphClass(): string
    {
        return \App\Models\User::class;
    }
}
