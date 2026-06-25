<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AIConversation extends Model
{
    use HasFactory;

    protected $table = 'ai_conversations';

    protected $fillable = [

        'uuid',

        'company_id',

        'user_id',

        'title',

        'provider',

        'model',

        'context',

        'is_active'
    ];

    protected $casts = [

        'context' => 'array',

        'is_active' => 'boolean'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Models\Company::class
        );
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Models\User::class
        );
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            AIMessage::class,
            'conversation_id'
        );
    }

    public function latestMessage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            AIMessage::class,
            'conversation_id'
        )->latestOfMany('id');
    }
}
