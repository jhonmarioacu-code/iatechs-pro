<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Policies;

use App\Models\User;

class AIAssistantPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'ai.view'
        );
    }

    public function chat(
        User $user
    ): bool {

        return $user->can(
            'ai.use'
        );
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'ai.use'
        );
    }

    public function delete(
        User $user
    ): bool {

        return $user->can(
            'ai.use'
        );
    }
}
