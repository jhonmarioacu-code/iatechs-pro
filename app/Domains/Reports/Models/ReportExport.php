<?php

declare(strict_types=1);

namespace App\Domains\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportExport extends Model
{
    use HasFactory;

    protected $fillable = [

        'report_id',

        'generated_by',

        'format',

        'file_name',

        'file_path',

        'file_size',

        'generated_at'
    ];

    protected $casts = [

        'generated_at' => 'datetime'
    ];

    public function report(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Report::class
        );
    }

    public function generatedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Domains\Users\Models\User::class,
            'generated_by'
        );
    }
}