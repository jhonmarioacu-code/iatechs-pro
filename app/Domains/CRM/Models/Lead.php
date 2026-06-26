<?php

declare(strict_types=1);

namespace App\Domains\CRM\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Companies\Models\Company;
use App\Domains\Users\Models\User;

class Lead extends Model
{
    use HasFactory;
    use BelongsToCompany;

    protected $table = 'crm_leads';

    protected $fillable = [

        'uuid',

        'company_id',

        'name',

        'email',

        'phone',

        'source',

        'status',

        'assigned_to'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Company::class
        );
    }

    public function assignedUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }

    public function opportunities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            Opportunity::class,
            'lead_id'
        );
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            Activity::class,
            'lead_id'
        );
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            Note::class,
            'lead_id'
        );
    }
}
