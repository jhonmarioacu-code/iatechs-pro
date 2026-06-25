<?php

declare(strict_types=1);

namespace App\Domains\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'crm_activities';

    protected $fillable = [

        'uuid',

        'lead_id',

        'opportunity_id',

        'type',

        'title',

        'description',

        'activity_date',

        'created_by'
    ];

    protected $casts = [

        'activity_date' => 'datetime'
    ];

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Lead::class,
            'lead_id'
        );
    }

    public function opportunity(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Opportunity::class,
            'opportunity_id'
        );
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'created_by'
        );
    }
}