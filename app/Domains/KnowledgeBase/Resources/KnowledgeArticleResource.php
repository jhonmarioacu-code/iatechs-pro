<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KnowledgeArticleResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'category_id' => $this->category_id,

            'title' => $this->title,

            'slug' => $this->slug,

            'summary' => $this->summary,

            'content' => $this->content,

            'status' => $this->status,

            'published_at' => $this->published_at,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}