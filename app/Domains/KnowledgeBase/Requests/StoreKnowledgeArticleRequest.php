<?php

declare(strict_types=1);

namespace App\Domains\KnowledgeBase\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKnowledgeArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'company_id' => [

                'required',

                'exists:companies,id'
            ],

            'category_id' => [

                'required',

                'exists:knowledge_categories,id'
            ],

            'title' => [

                'required',

                'string',

                'max:255'
            ],

            'summary' => [

                'nullable',

                'string'
            ],

            'content' => [

                'required',

                'string'
            ]
        ];
    }
}