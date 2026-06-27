<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dashboard extends Model
{
    use HasFactory;
    use BelongsToCompany;

    protected $fillable = [

        'uuid',

        'company_id',

        'name',

        'description',

        'is_default'
    ];

    protected $casts = [

        'is_default' => 'boolean'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    public function widgets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            DashboardWidget::class
        );
    }
}
