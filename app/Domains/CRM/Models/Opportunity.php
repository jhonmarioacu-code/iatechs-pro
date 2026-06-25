<?php

declare(strict_types=1);

namespace App\Domains\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunity extends Model
{
    use HasFactory;

    protected $table = 'crm_opportunities';

    protected $fillable = [

        'uuid',

        'lead_id',

        'title',

        'amount',

        'stage',

        'expected_close_date',

        'assigned_to'
    ];

    protected $casts = [

        'amount' => 'decimal:2',

        'expected_close_date' => 'date'
    ];

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Lead::class,
            'lead_id'
        );
    }

    public function assignedUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'assigned_to'
        );
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            Activity::class,
            'opportunity_id'
        );
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            Note::class,
            'opportunity_id'
        );
    }
}