<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Companies\Models\Company;

class Account extends Model
{
    use HasFactory;
    use BelongsToCompany;

    protected $table = 'accounts';

    protected $fillable = [

        'uuid',

        'company_id',

        'code',

        'name',

        'type',

        'parent_id',

        'is_active'
    ];

    protected $casts = [

        'is_active' => 'boolean'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Company::class
        );
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        );
    }

    public function journalLines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            JournalEntryLine::class,
            'account_id'
        );
    }
}
