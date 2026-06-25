<?php

declare(strict_types=1);

namespace App\Domains\Branches\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Domains\Branches\Models\Branch;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        $branch = $this->route('branch');

        return $branch instanceof Branch
            && ($this->user()?->can(
                'update',
                $branch
            ) ?? false);
    }

    public function rules(): array
    {
        /** @var Branch|null $branch */
        $branch = $this->route('branch');

        return [

            'name' => [
                'sometimes',
                'string',
                'min:2',
                'max:255'
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('branches', 'code')
                    ->ignore($branch?->id)
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
                'string',
                'max:1000'
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