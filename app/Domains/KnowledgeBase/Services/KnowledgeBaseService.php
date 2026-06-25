<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Services;

use Illuminate\Support\Str;

use App\Domains\KnowledgeBase\Models\KnowledgeArticle;

use App\Domains\KnowledgeBase\Repositories\KnowledgeArticleRepository;

class KnowledgeBaseService
{
    public function __construct(
        protected KnowledgeArticleRepository $articles
    ) {}

    public function paginateArticles(
        int $perPage = 20
    ) {
        return $this->articles
            ->query()
            ->latest()
            ->paginate($perPage);
    }

    public function createArticle(
        array $data
    ): KnowledgeArticle {

        $data['uuid'] = Str::uuid();

        $data['slug'] = Str::slug(
            $data['title']
        );

        return $this->articles->create(
            $data
        );
    }

    public function updateArticle(
        KnowledgeArticle $article,
        array $data
    ): KnowledgeArticle {

        if (isset($data['title'])) {

            $data['slug'] = Str::slug(
                $data['title']
            );
        }

        return $this->articles->update(
            $article,
            $data
        );
    }

    public function publishArticle(
        KnowledgeArticle $article
    ): KnowledgeArticle {

        return $this->articles->update(
            $article,
            [

                'status' => 'published',

                'published_at' => now()
            ]
        );
    }

    public function archiveArticle(
        KnowledgeArticle $article
    ): KnowledgeArticle {

        return $this->articles->update(
            $article,
            [

                'status' => 'archived'
            ]
        );
    }

    public function deleteArticle(
        KnowledgeArticle $article
    ): bool {
        return $this->articles
            ->delete($article);
    }

    public function search(
        string $term
    )
    {
        return $this->articles
            ->query()

            ->where(
                'status',
                'published'
            )

            ->where(function ($query) use ($term) {

                $query->where(
                    'title',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'summary',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'content',
                    'like',
                    "%{$term}%"
                );
            })

            ->latest()

            ->paginate(20);
    }
}
