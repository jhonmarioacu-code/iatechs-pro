<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Repositories;

use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\AIAssistant\Models\AIConversation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AIConversationRepository
{
    use ProvidesRepositoryDefaults;

    public function create(
        array $data
    ): AIConversation {
        return AIConversation::create($data);
    }

    public function findOrFail(
        int $id
    ): AIConversation {
        return AIConversation::query()
            ->findOrFail($id);
    }

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {
        return AIConversation::query()
            ->latest()
            ->paginate($perPage);
    }
}
