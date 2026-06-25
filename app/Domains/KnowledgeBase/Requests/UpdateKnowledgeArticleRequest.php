<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKnowledgeArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'category_id' => [

                'sometimes',

                'exists:knowledge_categories,id'
            ],

            'title' => [

                'sometimes',

                'string',

                'max:255'
            ],

            'summary' => [

                'nullable',

                'string'
            ],

            'content' => [

                'sometimes',

                'string'
            ],

            'status' => [

                'sometimes',

                'in:draft,published,archived'
            ]
        ];
    }
}