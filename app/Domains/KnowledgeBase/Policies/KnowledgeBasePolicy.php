<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Policies;

use App\Domains\Users\Models\User;
use App\Domains\KnowledgeBase\Models\KnowledgeArticle;

class KnowledgeBasePolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'knowledge.view'
        );
    }

    public function view(
        User $user,
        KnowledgeArticle $article
    ): bool {

        return $user->can(
            'knowledge.view'
        );
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'knowledge.create'
        );
    }

    public function update(
        User $user,
        KnowledgeArticle $article
    ): bool {

        return $user->can(
            'knowledge.update'
        );
    }

    public function delete(
        User $user,
        KnowledgeArticle $article
    ): bool {

        return $user->can(
            'knowledge.delete'
        );
    }

    public function publish(
        User $user
    ): bool {

        return $user->can(
            'knowledge.update'
        );
    }

    public function archive(
        User $user
    ): bool {

        return $user->can(
            'knowledge.update'
        );
    }
}
