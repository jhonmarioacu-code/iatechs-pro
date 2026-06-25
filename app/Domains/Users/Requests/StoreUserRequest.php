<?php

declare(strict_types=1);

namespace App\Domains\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('users.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            $this->merge([
                'company_id' => $user->company_id
            ]);
        }
    }

    public function rules(): array
    {
        return [

            'company_id' => [
                'required',
                Rule::exists('companies', 'id')
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'min:8'
            ],

            'phone' => [
                'nullable',
                'string'
            ],

            'role' => [
                'required',
                Rule::exists('roles', 'name')
                    ->where('guard_name', 'web'),
                Rule::notIn(
                    $this->user()?->hasRole('super_admin')
                        ? []
                        : ['super_admin']
                )
            ]
        ];
    }
}
