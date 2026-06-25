<?php

declare(strict_types=1);

namespace App\Domains\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Domains\Customers\Models\Customer;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $customer = $this->route('customer');

        return $customer instanceof Customer
            && (
                $this->user()?->can(
                    'update',
                    $customer
                ) ?? false
            );
    }

    public function rules(): array
    {
        return [

            'customer_type' => [
                'sometimes',
                'in:person,company'
            ],

            'first_name' => [
                'sometimes',
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
            $customer = $this->route('customer');
            if (!$customer instanceof Customer) {
                return;
            }

            $customerType =
                $this->input(
                    'customer_type',
                    $customer->customer_type
                );

            if (
                $customerType === 'person'
                &&
                empty(
                    $this->input('first_name')
                )
                &&
                empty(
                    $customer->first_name
                )
            ) {
                $validator->errors()->add(
                    'first_name',
                    'First name is required for person customers.'
                );
            }

            if (
                $customerType === 'company'
                &&
                empty(
                    $this->input('company_name')
                )
                &&
                empty(
                    $customer->company_name
                )
            ) {
                $validator->errors()->add(
                    'company_name',
                    'Company name is required for company customers.'
                );
            }
        });
    }
}
