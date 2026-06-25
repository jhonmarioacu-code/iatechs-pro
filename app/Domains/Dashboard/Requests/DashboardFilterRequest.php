<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'from' => [
                'nullable',
                'date'
            ],

            'to' => [
                'nullable',
                'date'
            ],

            'company_id' => [
                'nullable',
                'exists:companies,id'
            ],

            'widget' => [
                'nullable',
                'string'
            ]
        ];
    }
}
