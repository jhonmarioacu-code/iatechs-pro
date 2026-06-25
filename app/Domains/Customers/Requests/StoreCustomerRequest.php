<?php

declare(strict_types=1);

namespace App\Domains\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Domains\Customers\Models\Customer;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'create',
            Customer::class
        ) ?? false;
    }

    public function rules(): array
    {
        return [

            'branch_id' => [
                'required',
                Rule::exists(
                    'branches',
                    'id'
                )
            ],

            'customer_type' => [
                'required',
                Rule::in([
                    'person',
                    'company'
                ])
            ],

            'first_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'document_type' => [
                'nullable',
                'string',
                'max:50'
            ],

            'document_number' => [
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
                'max:50'
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:50'
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

            'credit_limit' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'notes' => [
                'nullable',
                'string'
            ],

            'accepts_marketing' => [
                'nullable',
                'boolean'
            ],

            'is_active' => [
                'nullable',
                'boolean'
            ]
        ];
    }

    public function withValidator(
        $validator
    ): void {

        $validator->after(function ($validator) {

            if (
                $this->customer_type === 'person'
                &&
                empty($this->first_name)
            ) {
                $validator->errors()->add(
                    'first_name',
                    'First name is required for person customers.'
                );
            }

            if (
                $this->customer_type === 'company'
                &&
                empty($this->company_name)
            ) {
                $validator->errors()->add(
                    'company_name',
                    'Company name is required for company customers.'
                );
            }
        });
    }
}