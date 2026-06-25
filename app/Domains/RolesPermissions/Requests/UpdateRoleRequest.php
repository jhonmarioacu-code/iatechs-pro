<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')->id;

        return [

            'name' => [

                'required',
                'string',
                'max:100',

                Rule::unique('roles', 'name')
                    ->ignore($roleId)
            ],

            'permissions' => [
                'nullable',
                'array'
            ],

            'permissions.*' => [
                'string',
                'exists:permissions,name'
            ]
        ];
    }
}