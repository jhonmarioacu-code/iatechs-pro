<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use BelongsToCompany;

    protected $fillable = [

        'uuid',

        'company_id',

        'user_id',

        'title',

        'message',

        'type',

        'channel',

        'status',

        'recipient',

        'subject',

        'error_message',

        'data',

        'sent_at',

        'delivered_at',

        'read_at'
    ];

    protected $casts = [

        'data' => 'array',

        'sent_at' => 'datetime',

        'delivered_at' => 'datetime',

        'read_at' => 'datetime'
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
