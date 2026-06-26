<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Controllers;

use App\Domains\AIAssistant\Models\AIConversation;
use App\Domains\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use App\Domains\AIAssistant\Requests\ChatRequest;
use App\Domains\AIAssistant\Resources\AIMessageResource;

use App\Domains\AIAssistant\Services\AIAssistantService;

class AIAssistantController extends Controller
{
    public function __construct(
        protected AIAssistantService $service
    ) {}

    public function chat(
        ChatRequest $request
    ): JsonResponse
    {
        $this->authorize('chat', AIConversation::class);

        /** @var User $user */
        $user = $request->user();

        $conversationId = $request->input('conversation_id');

        if ($conversationId !== null) {
            $conversation = $this->service
                ->findConversation(
                    (int) $conversationId,
                    (int) $user->id,
                    (int) $user->company_id
                );
        } else {
            $title = mb_substr(trim((string) $request->message), 0, 80);
            $conversation = $this->service->createConversation(
                (int) $user->company_id,
                (int) $user->id,
                $title !== '' ? $title : 'Nueva conversacion'
            );
        }

        $response = $this->service->sendMessage(
            $user,
            $conversation,
            $request->message
        );

        $response['conversation_id'] = $conversation->id;

        return response()->json(
            $response
        );
    }

    public function conversations(): LengthAwarePaginator
    {
        $this->authorize('viewAny', AIConversation::class);

        /** @var User $user */
        $user = request()->user();

        $paginator = $this->service
            ->paginateConversations(
                (int) $user->id,
                (int) $user->company_id
            );

        $paginator->setCollection(
            $paginator->getCollection()->map(static function ($conversation) {
                $latestMessage = $conversation->latestMessage;

                $conversation->setAttribute(
                    'last_message_preview',
                    $latestMessage ? mb_substr((string) $latestMessage->content, 0, 120) : ''
                );

                $conversation->setAttribute(
                    'last_message_role',
                    $latestMessage?->role
                );

                $conversation->setAttribute(
                    'last_message_at',
                    $latestMessage?->created_at
                );

                return $conversation;
            })
        );

        return $paginator;
    }

    public function messages(
        Request $request,
        int $conversation
    ): AnonymousResourceCollection {
        $this->authorize('viewAny', AIConversation::class);

        /** @var User $user */
        $user = $request->user();

        $messages = $this->service->listConversationMessages(
            $conversation,
            (int) $user->id,
            (int) $user->company_id
        );

        return AIMessageResource::collection($messages);
    }
}
