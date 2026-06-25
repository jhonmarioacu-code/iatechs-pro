<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardWidget extends Model
{
    use HasFactory;

    protected $fillable = [

        'dashboard_id',

        'widget',

        'position',

        'width',

        'height',

        'settings',

        'enabled'
    ];

    protected $casts = [

        'settings' => 'array',

        'enabled' => 'boolean'
    ];

    public function dashboard(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Dashboard::class
        );
    }
}
