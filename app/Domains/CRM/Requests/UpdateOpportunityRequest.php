<?php

declare(strict_types=1);

namespace App\Domains\CRM\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOpportunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'title' => [
                'sometimes',
                'string',
                'max:255'
            ],

            'amount' => [
                'nullable',
                'numeric'
            ],

            'stage' => [
                'sometimes',
                'in:prospecting,qualification,proposal,negotiation,won,lost'
            ],

            'expected_close_date' => [
                'nullable',
                'date'
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id'
            ]
        ];
    }
}