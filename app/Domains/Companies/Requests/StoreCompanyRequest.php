<?php

declare(strict_types=1);

namespace App\Domains\Companies\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
                'max:255',
                'unique:companies,name'
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
                'unique:companies,tax_id'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:companies,email'
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

            'trial_ends_at' => [
                'nullable',
                'date'
            ]
        ];
    }
}