<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Models;

use App\Tenant\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Companies\Models\Company;
use App\Domains\Users\Models\User;

class KnowledgeArticle extends Model
{
    use HasFactory;
    use BelongsToCompany;

    protected $table = 'knowledge_articles';

    protected $fillable = [

        'uuid',

        'company_id',

        'category_id',

        'title',

        'slug',

        'summary',

        'content',

        'status',

        'published_at',

        'created_by',

        'updated_by'
    ];

    protected $casts = [

        'published_at' => 'datetime'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Company::class
        );
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            KnowledgeCategory::class,
            'category_id'
        );
    }

    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            KnowledgeAttachment::class,
            'article_id'
        );
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}
