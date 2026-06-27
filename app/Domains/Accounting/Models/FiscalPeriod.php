<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Companies\Models\Company;

class FiscalPeriod extends Model
{
    use HasFactory;
    use BelongsToCompany;

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
            Company::class
        );
    }
}
