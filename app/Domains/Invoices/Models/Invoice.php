<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Tenant\Traits\BelongsToCompany;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToCompany;

    protected $fillable = [

        'uuid',

        'company_id',
        'branch_id',

        'customer_id',

        'billing_id',

        'ticket_id',
        'repair_id',

        'invoice_series',
        'invoice_number',

        'status',

        'subtotal',
        'tax',
        'discount',
        'total',

        'currency',
        'exchange_rate',

        'issued_at',
        'due_date',
        'paid_at',

        'notes',
    ];

    protected $casts = [

        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'exchange_rate' => 'decimal:4',

        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
        'due_date' => 'date',
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }

    public function billing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Billing\Models\Billing::class
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

    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Tickets\Models\Ticket::class
        );
    }

    public function repair(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Repairs\Models\Repair::class
        );
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            InvoiceItem::class
        );
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            \App\Domains\Payments\Models\Payment::class
        );
    }
}
