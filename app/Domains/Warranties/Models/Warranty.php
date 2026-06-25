<?php

declare(strict_types=1);

namespace App\Domains\Warranties\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warranty extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'company_id',
        'branch_id',

        'customer_id',
        'device_id',

        'repair_id',
        'invoice_id',

        'warranty_number',

        'type',
        'status',

        'start_date',
        'end_date',

        'terms',
        'notes',
    ];

    protected $casts = [

        'start_date' => 'date',

        'end_date' => 'date',
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

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Customers\Models\Customer::class
        );
    }

    public function device(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Devices\Models\Device::class
        );
    }

    public function repair(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Repairs\Models\Repair::class
        );
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Invoices\Models\Invoice::class
        );
    }
}