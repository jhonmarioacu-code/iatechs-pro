<?php

declare(strict_types=1);

namespace App\Domains\Devices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Tenant\Traits\BelongsToCompany;

class Device extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $fillable = [

        'company_id',
        'branch_id',
        'customer_id',
        'uuid',

        'device_type',

        'brand',
        'model',

        'serial_number',
        'imei',

        'color',

        'accessories',
        'observations',
        'manual_url',
        'diagram_url',
        'boardview_url',
        'boardview_enabled',

        'is_active'
    ];

    protected $casts = [
        'boardview_enabled' => 'boolean',
        'is_active' => 'boolean'
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
}
