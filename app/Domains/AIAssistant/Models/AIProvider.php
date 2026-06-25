<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AIProvider extends Model
{
    use HasFactory;

    protected $table = 'ai_providers';

    protected $fillable = [

        'uuid',

        'name',

        'driver',

        'model',

        'enabled',

        'is_default',

        'priority',

        'max_tokens',

        'input_cost_per_million',

        'output_cost_per_million',

        'configuration'
    ];

    protected $casts = [

        'enabled' => 'boolean',

        'is_default' => 'boolean',

        'configuration' => 'array'
    ];
}