<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Tenant\Traits\BelongsToCompany;

class Repair extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $fillable = [

        'uuid',

        'company_id',
        'branch_id',

        'ticket_id',
        'diagnostic_id',
        'quote_id',

        'technician_id',

        'repair_number',

        'status',

        'repair_notes',

        'labor_cost',
        'parts_cost',
        'total_cost',

        'started_at',
        'completed_at',
        'delivered_at',
    ];

    protected $casts = [

        'labor_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',

        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'delivered_at' => 'datetime',
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

    public function diagnostic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Diagnostics\Models\Diagnostic::class
        );
    }

    public function quote(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Quotes\Models\Quote::class
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
