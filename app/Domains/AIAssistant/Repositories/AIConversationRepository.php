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

    public function findForUserOrFail(
        int $id,
        int $userId,
        int $companyId
    ): AIConversation {
        return AIConversation::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->where('company_id', $companyId)
            ->findOrFail($id);
    }

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {
        return AIConversation::query()
            ->latest()
            ->paginate($perPage);
    }

    public function paginateForUser(
        int $userId,
        int $companyId,
        int $perPage = 20
    ): LengthAwarePaginator {
        return AIConversation::query()
            ->where('user_id', $userId)
            ->where('company_id', $companyId)
            ->with('latestMessage')
            ->latest()
            ->paginate($perPage);
    }
}
