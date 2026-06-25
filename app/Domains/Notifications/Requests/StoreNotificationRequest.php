<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
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

            'user_id' => [
                'nullable',
                'exists:users,id'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'message' => [
                'required',
                'string'
            ],

            'type' => [
                'required',
                'string'
            ],

            'channel' => [
                'required',
                'string'
            ],

            'recipient' => [
                'nullable',
                'string'
            ],

            'subject' => [
                'nullable',
                'string'
            ],

            'data' => [
                'nullable',
                'array'
            ]
        ];
    }
}