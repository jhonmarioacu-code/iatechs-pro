<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\KnowledgeBase\Models\KnowledgeArticle;

use App\Domains\KnowledgeBase\Services\KnowledgeBaseService;

use App\Domains\KnowledgeBase\Resources\KnowledgeArticleResource;

use App\Domains\KnowledgeBase\Requests\StoreKnowledgeArticleRequest;
use App\Domains\KnowledgeBase\Requests\UpdateKnowledgeArticleRequest;

class KnowledgeBaseController extends Controller
{
    public function __construct(
        protected KnowledgeBaseService $service
    ) {}

    public function index()
    {
        return KnowledgeArticleResource::collection(
            $this->service->paginateArticles()
        );
    }

    public function store(
        StoreKnowledgeArticleRequest $request
    )
    {
        $article = $this->service
            ->createArticle(
                $request->validated()
            );

        return new KnowledgeArticleResource(
            $article
        );
    }

    public function show(
        KnowledgeArticle $knowledgeArticle
    )
    {
        return new KnowledgeArticleResource(
            $knowledgeArticle
        );
    }

    public function update(
        UpdateKnowledgeArticleRequest $request,
        KnowledgeArticle $knowledgeArticle
    )
    {
        $article = $this->service
            ->updateArticle(
                $knowledgeArticle,
                $request->validated()
            );

        return new KnowledgeArticleResource(
            $article
        );
    }

    public function destroy(
        KnowledgeArticle $knowledgeArticle
    )
    {
        $this->service
            ->deleteArticle(
                $knowledgeArticle
            );

        return response()->json([
            'message' => 'Artículo eliminado'
        ]);
    }

    public function publish(
        KnowledgeArticle $knowledgeArticle
    )
    {
        return new KnowledgeArticleResource(

            $this->service
                ->publishArticle(
                    $knowledgeArticle
                )
        );
    }

    public function archive(
        KnowledgeArticle $knowledgeArticle
    )
    {
        return new KnowledgeArticleResource(

            $this->service
                ->archiveArticle(
                    $knowledgeArticle
                )
        );
    }
}