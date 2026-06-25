<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Policies;

use App\Models\User;
use App\Domains\KnowledgeBase\Models\KnowledgeArticle;

class KnowledgeBasePolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'knowledge-base.view'
        );
    }

    public function view(
        User $user,
        KnowledgeArticle $article
    ): bool {

        return $user->can(
            'knowledge-base.view'
        );
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'knowledge-base.create'
        );
    }

    public function update(
        User $user,
        KnowledgeArticle $article
    ): bool {

        return $user->can(
            'knowledge-base.update'
        );
    }

    public function delete(
        User $user,
        KnowledgeArticle $article
    ): bool {

        return $user->can(
            'knowledge-base.delete'
        );
    }

    public function publish(
        User $user
    ): bool {

        return $user->can(
            'knowledge-base.publish'
        );
    }

    public function archive(
        User $user
    ): bool {

        return $user->can(
            'knowledge-base.archive'
        );
    }
}