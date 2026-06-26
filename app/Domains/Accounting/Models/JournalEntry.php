<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Companies\Models\Company;
use App\Domains\Users\Models\User;

class JournalEntry extends Model
{
    use HasFactory;
    use BelongsToCompany;

    protected $table = 'journal_entries';

    protected $fillable = [

        'uuid',

        'company_id',

        'entry_number',

        'entry_date',

        'description',

        'status',

        'created_by'
    ];

    protected $casts = [

        'entry_date' => 'date'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Company::class
        );
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function lines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            JournalEntryLine::class,
            'journal_entry_id'
        );
    }
}
