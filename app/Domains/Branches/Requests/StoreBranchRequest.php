<?php

declare(strict_types=1);

namespace App\Domains\Branches\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Domains\Branches\Models\Branch;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'create',
            Branch::class
        ) ?? false;
    }

    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'code' => [
                'nullable',
                'string',
                'max:50'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255'
            ],

            'manager_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'address' => [
                'nullable',
                'string'
            ],

            'city' => [
                'nullable',
                'string',
                'max:100'
            ],

            'state' => [
                'nullable',
                'string',
                'max:100'
            ],

            'country' => [
                'nullable',
                'string',
                'max:100'
            ],

            'is_main' => [
                'nullable',
                'boolean'
            ],

            'is_active' => [
                'nullable',
                'boolean'
            ]
        ];
    }
}