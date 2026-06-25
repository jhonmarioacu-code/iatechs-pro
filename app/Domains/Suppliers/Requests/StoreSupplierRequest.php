<?php

declare(strict_types=1);

namespace App\Domains\Suppliers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'company_id' => [
                'required',
                'exists:companies,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'legal_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'tax_id' => [
                'nullable',
                'string',
                'max:100'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:100'
            ],

            'website' => [
                'nullable',
                'url'
            ],

            'contact_name' => [
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
                'max:255'
            ],

            'state' => [
                'nullable',
                'string',
                'max:255'
            ],

            'country' => [
                'nullable',
                'string',
                'max:255'
            ],

            'metadata' => [
                'nullable',
                'array'
            ]
        ];
    }
}