<?php

declare(strict_types=1);

namespace App\Domains\Companies\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')->id;

        return [

            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('companies', 'name')
                    ->ignore($companyId)
            ],

            'legal_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'tax_id' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('companies', 'tax_id')
                    ->ignore($companyId)
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('companies', 'email')
                    ->ignore($companyId)
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50'
            ],

            'website' => [
                'nullable',
                'url',
                'max:255'
            ],

            'address' => [
                'nullable',
                'string',
                'max:255'
            ],

            'city' => [
                'nullable',
                'string',
                'max:100'
            ],

            'country' => [
                'nullable',
                'string',
                'max:100'
            ],

            'logo' => [
                'nullable',
                'string'
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'active',
                    'suspended',
                    'cancelled'
                ])
            ],

            'trial_ends_at' => [
                'nullable',
                'date'
            ]
        ];
    }
}