<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Repositories;

use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\AIAssistant\Models\AIMessage;

class AIMessageRepository
{
    use ProvidesRepositoryDefaults;

    public function create(
        array $data
    ): AIMessage {
        return AIMessage::create($data);
    }
}
