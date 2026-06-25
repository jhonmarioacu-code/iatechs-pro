<?php

declare(strict_types=1);

namespace App\Domains\AuditLogs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [

        'uuid',

        'company_id',

        'user_id',

        'event',

        'entity_type',

        'entity_id',

        'old_values',

        'new_values',

        'ip_address',

        'user_agent',

        'url',

        'method',

        'module',

        'occurred_at',
    ];

    protected $casts = [

        'old_values' => 'array',

        'new_values' => 'array',

        'occurred_at' => 'datetime',
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
}