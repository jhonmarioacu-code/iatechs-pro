<?php

declare(strict_types=1);

namespace App\Domains\Quotes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = [

        'quote_id',

        'type',

        'name',

        'description',

        'quantity',

        'unit_price',

        'total',

        'sort_order',
    ];

    protected $casts = [

        'quantity' => 'decimal:2',

        'unit_price' => 'decimal:2',

        'total' => 'decimal:2',
    ];

    public function quote(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Quote::class
        );
    }
}