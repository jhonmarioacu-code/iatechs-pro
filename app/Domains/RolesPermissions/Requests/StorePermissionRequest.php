<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:150',
                'unique:permissions,name'
            ]
        ];
    }
}