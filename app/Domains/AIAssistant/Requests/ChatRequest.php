<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'conversation_id' => [

                'required',

                'exists:ai_conversations,id'
            ],

            'message' => [

                'required',

                'string',

                'max:10000'
            ]
        ];
    }
}