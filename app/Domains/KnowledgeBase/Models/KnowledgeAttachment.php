<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KnowledgeAttachment extends Model
{
    use HasFactory;

    protected $table = 'knowledge_attachments';

    protected $fillable = [

        'uuid',

        'article_id',

        'file_name',

        'original_name',

        'mime_type',

        'file_size',

        'disk',

        'path'
    ];

    public function article(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            KnowledgeArticle::class,
            'article_id'
        );
    }
}