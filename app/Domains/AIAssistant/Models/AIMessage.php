<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AIMessage extends Model
{
    use HasFactory;

    protected $table = 'ai_messages';

    protected $fillable = [

        'conversation_id',

        'role',

        'content',

        'tokens',

        'provider',

        'model',

        'cost'
    ];

    protected $casts = [

        'cost' => 'decimal:6'
    ];

    public function conversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            AIConversation::class,
            'conversation_id'
        );
    }
}