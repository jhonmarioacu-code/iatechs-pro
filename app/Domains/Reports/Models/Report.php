<?php

declare(strict_types=1);

namespace App\Domains\Reports\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    use BelongsToCompany;

    protected $fillable = [

        'uuid',

        'company_id',

        'user_id',

        'name',

        'type',

        'filters',

        'total_records',

        'status',

        'generated_at'
    ];

    protected $casts = [

        'filters' => 'array',

        'generated_at' => 'datetime'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class
        );
    }

    public function exports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            ReportExport::class
        );
    }
}
