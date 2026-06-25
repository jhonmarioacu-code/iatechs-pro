<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FiscalPeriod extends Model
{
    use HasFactory;

    protected $table = 'fiscal_periods';

    protected $fillable = [

        'uuid',

        'company_id',

        'name',

        'start_date',

        'end_date',

        'is_closed',

        'closed_at'
    ];

    protected $casts = [

        'start_date' => 'date',

        'end_date' => 'date',

        'closed_at' => 'datetime',

        'is_closed' => 'boolean'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Models\Company::class
        );
    }
}