<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class NotificationChannel extends Model
{
    use BelongsToCompany;

    protected $fillable = [

        'company_id',

        'channel',

        'enabled',

        'configuration'
    ];

    protected $casts = [

        'enabled' => 'boolean',

        'configuration' => 'array'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }
}
