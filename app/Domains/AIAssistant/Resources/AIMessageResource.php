<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AIMessageResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'conversation_id' =>
                $this->conversation_id,

            'role' =>
                $this->role,

            'content' =>
                $this->content,

            'tokens' =>
                $this->tokens,

            'provider' =>
                $this->provider,

            'model' =>
                $this->model,

            'cost' =>
                $this->cost,

            'created_at' =>
                $this->created_at,

            'updated_at' =>
                $this->updated_at,
        ];
    }
}