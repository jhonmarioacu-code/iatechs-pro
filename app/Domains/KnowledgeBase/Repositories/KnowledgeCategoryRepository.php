<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\KnowledgeBase\Models\KnowledgeCategory;

class KnowledgeCategoryRepository
{
    use ProvidesRepositoryDefaults;

    public function query()
    {
        return KnowledgeCategory::query();
    }

    public function find(
        int $id
    ): ?KnowledgeCategory {

        return KnowledgeCategory::find($id);
    }

    public function create(
        array $data
    ): KnowledgeCategory {

        return KnowledgeCategory::create(
            $data
        );
    }

    public function update(
        KnowledgeCategory $category,
        array $data
    ): KnowledgeCategory {

        $category->update($data);

        return $category->fresh();
    }

    public function delete(
        KnowledgeCategory $category
    ): bool {

        return $category->delete();
    }
}