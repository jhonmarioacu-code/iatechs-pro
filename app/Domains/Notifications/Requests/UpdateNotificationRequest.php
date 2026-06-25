<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
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

            'message' => [
                'sometimes',
                'string'
            ],

            'status' => [
                'sometimes',
                'string'
            ]
        ];
    }
}