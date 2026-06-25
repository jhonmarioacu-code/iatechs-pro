<?php

declare(strict_types=1);

namespace App\Domains\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? null;

        return [

            'company_id' => [
                'sometimes',
                'exists:companies,id'
            ],

            'name' => [
                'sometimes',
                'string',
                'max:255'
            ],

            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($userId)
            ],

            'password' => [
                'nullable',
                'string',
                'min:8'
            ],

            'status' => [
                'nullable',
                'boolean'
            ]
        ];
    }
}