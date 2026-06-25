<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Domains\Users\Models\User;
use App\Domains\AIAssistant\Models\AIConversation;
use App\Domains\AIAssistant\Repositories\AIMessageRepository;
use App\Domains\AIAssistant\Repositories\AIConversationRepository;

class AIAssistantService
{
    public function __construct(
        protected AIManager $manager,
        protected AIDocumentationContextService $documentationContext,
        protected AIConversationRepository $conversations,
        protected AIMessageRepository $messages
    ) {}

    public function paginateConversations(
        int $userId,
        int $companyId,
        int $perPage = 20
    ) {
        return $this->conversations
            ->paginateForUser($userId, $companyId, $perPage);
    }

    public function findConversation(
        int $id,
        int $userId,
        int $companyId
    ): AIConversation {
        return $this->conversations
            ->findForUserOrFail($id, $userId, $companyId);
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
        User $user,
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

        $messages = $this->prependSystemContext($messages, $user);

        $response = $this->manager->chat(
            $messages,
            [
                'max_output_tokens' => 900,
                'temperature' => 0.2,
            ]
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

    public function listConversationMessages(
        int $conversationId,
        int $userId,
        int $companyId
    ): Collection {
        $conversation = $this->findConversation(
            $conversationId,
            $userId,
            $companyId
        );

        return $conversation->messages()
            ->orderBy('id')
            ->get();
    }

    /**
     * @param array<int, array<string, mixed>> $messages
     * @return array<int, array<string, string>>
     */
    protected function prependSystemContext(array $messages, User $user): array
    {
        $role = (string) ($user->getRoleNames()->first() ?? 'user');

        $systemContext = $this->documentationContext->getContextByRole($role);
        $identityContext = implode("\n", [
            'Contexto de usuario:',
            '- role: '.$role,
            '- company_id: '.(string) $user->company_id,
            '- language: es',
        ]);

        $systemMessage = [
            'role' => 'system',
            'content' => $systemContext."\n\n".$identityContext,
        ];

        /** @var array<int, array<string, string>> $payload */
        $payload = [$systemMessage];

        foreach ($messages as $message) {
            $payload[] = [
                'role' => (string) ($message['role'] ?? 'user'),
                'content' => (string) ($message['content'] ?? ''),
            ];
        }

        return $payload;
    }
}
