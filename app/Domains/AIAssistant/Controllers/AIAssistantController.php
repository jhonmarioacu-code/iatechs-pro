<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\AIAssistant\Requests\ChatRequest;

use App\Domains\AIAssistant\Services\AIAssistantService;

use App\Domains\AIAssistant\Resources\AIMessageResource;

class AIAssistantController extends Controller
{
    public function __construct(
        protected AIAssistantService $service
    ) {}

    public function chat(
        ChatRequest $request
    )
    {
        $conversation = $this->service
            ->findConversation(
                (int) $request->conversation_id
            );

        $response = $this->service->sendMessage(
            $conversation,
            $request->message
        );

        return response()->json(
            $response
        );
    }

    public function conversations()
    {
        return $this->service
            ->paginateConversations();
    }
}
