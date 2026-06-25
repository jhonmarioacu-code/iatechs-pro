<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KnowledgeCategory extends Model
{
    use HasFactory;

    protected $table = 'knowledge_categories';

    protected $fillable = [

        'uuid',

        'company_id',

        'name',

        'slug',

        'description',

        'is_active'
    ];

    protected $casts = [

        'is_active' => 'boolean'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            \App\Models\Company::class
        );
    }

    public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            KnowledgeArticle::class,
            'category_id'
        );
    }
}