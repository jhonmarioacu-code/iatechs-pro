<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Services;

use Illuminate\Support\Str;

use App\Domains\AIAssistant\Models\AIConversation;
use App\Domains\AIAssistant\Repositories\AIMessageRepository;
use App\Domains\AIAssistant\Repositories\AIConversationRepository;

class AIAssistantService
{
    public function __construct(
        protected AIManager $manager,
        protected AIConversationRepository $conversations,
        protected AIMessageRepository $messages
    ) {}

    public function paginateConversations(
        int $perPage = 20
    ) {
        return $this->conversations
            ->paginate($perPage);
    }

    public function findConversation(
        int $id
    ): AIConversation {
        return $this->conversations
            ->findOrFail($id);
    }

    public function createConversation(
        int $companyId,
        int $userId,
        string $title = 'Nueva conversación'
    ): AIConversation {

        return $this->conversations->create([

            'uuid' => Str::uuid(),

            'company_id' => $companyId,

            'user_id' => $userId,

            'title' => $title,

            'provider' => config('ai.provider'),

            'model' => config('ai.model'),

            'is_active' => true
        ]);
    }

    public function sendMessage(
        AIConversation $conversation,
        string $message
    ): array {

        $this->messages->create([

            'conversation_id' => $conversation->id,

            'role' => 'user',

            'content' => $message,
        ]);

        $messages = $conversation
            ->messages()
            ->orderBy('id')
            ->get([
                'role',
                'content'
            ])
            ->toArray();

        $response = $this->manager->chat(
            $messages
        );

        $content =
            $response['choices'][0]['message']['content']
            ?? 'Sin respuesta';

        $this->messages->create([

            'conversation_id' => $conversation->id,

            'role' => 'assistant',

            'content' => $content,

            'provider' => config('ai.provider'),

            'model' => config('ai.model')
        ]);

        return [

            'message' => $content
        ];
    }
}
