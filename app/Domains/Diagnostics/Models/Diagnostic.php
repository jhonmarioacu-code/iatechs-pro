<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Tenant\Traits\BelongsToCompany;

class Diagnostic extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $fillable = [

        'uuid',

        'company_id',
        'branch_id',

        'ticket_id',
        'technician_id',

        'diagnostic_code',

        'status',

        'reported_problem',
        'diagnostic_result',

        'recommended_solution',

        'estimated_cost',
        'estimated_hours',

        'started_at',
        'finished_at',
    ];

    protected $casts = [

        'estimated_cost' => 'decimal:2',

        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Branches\Models\Branch::class
        );
    }

    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Tickets\Models\Ticket::class
        );
    }

    public function technician(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class,
            'technician_id'
        );
    }
}
