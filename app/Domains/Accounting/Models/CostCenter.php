<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Companies\Models\Company;

class CostCenter extends Model
{
    use HasFactory;
    use BelongsToCompany;

    protected $table = 'cost_centers';

    protected $fillable = [

        'uuid',

        'company_id',

        'code',

        'name',

        'description',

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
}
