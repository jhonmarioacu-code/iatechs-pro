<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\KnowledgeBase\Models\KnowledgeArticle;

class KnowledgeArticleRepository
{
    use ProvidesRepositoryDefaults;

    public function query()
    {
        return KnowledgeArticle::query();
    }

    public function find(
        int $id
    ): ?KnowledgeArticle {

        return KnowledgeArticle::find($id);
    }

    public function findByUuid(
        string $uuid
    ): ?KnowledgeArticle {

        return KnowledgeArticle::where(
            'uuid',
            $uuid
        )->first();
    }

    public function create(
        array $data
    ): KnowledgeArticle {

        return KnowledgeArticle::create(
            $data
        );
    }

    public function update(
        KnowledgeArticle $article,
        array $data
    ): KnowledgeArticle {

        $article->update($data);

        return $article->fresh();
    }

    public function delete(
        KnowledgeArticle $article
    ): bool {

        return $article->delete();
    }
}