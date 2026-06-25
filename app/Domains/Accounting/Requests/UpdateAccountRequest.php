<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'code' => [
                'sometimes',
                'string',
                'max:50'
            ],

            'name' => [
                'sometimes',
                'string',
                'max:255'
            ],

            'type' => [
                'sometimes',
                'in:asset,liability,equity,income,expense'
            ],

            'is_active' => [
                'sometimes',
                'boolean'
            ]
        ];
    }
}