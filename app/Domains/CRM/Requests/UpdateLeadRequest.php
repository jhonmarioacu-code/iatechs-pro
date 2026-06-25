<?php

declare(strict_types=1);

namespace App\Domains\CRM\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => [
                'sometimes',
                'string',
                'max:255'
            ],

            'email' => [
                'nullable',
                'email'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50'
            ],

            'status' => [
                'sometimes',
                'in:new,contacted,qualified,converted,lost'
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id'
            ]
        ];
    }
}