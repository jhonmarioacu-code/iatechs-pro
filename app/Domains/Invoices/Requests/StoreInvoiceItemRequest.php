<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceItemRequest extends FormRequest
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        return $this->user()?->can(
            'invoices.create'
        ) ?? false;
    }

    /**
     * MultiTenant
     */
    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if (
            $user &&
            !$user->hasRole('super_admin')
        ) {
            $this->merge([
                'company_id' => $user->company_id
            ]);
        }
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'company_id' => [
                'required',
                Rule::exists(
                    'companies',
                    'id'
                ),
            ],

            'invoice_id' => [
                'required',
                Rule::exists(
                    'invoices',
                    'id'
                ),
            ],

            'product_id' => [
                'nullable',
                Rule::exists(
                    'products',
                    'id'
                ),
            ],

            'type' => [
                'required',
                Rule::in([
                    'product',
                    'service',
                    'repair',
                    'part',
                ]),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'company_id.required' =>
                'Company is required.',

            'invoice_id.required' =>
                'Invoice is required.',

            'invoice_id.exists' =>
                'Invoice not found.',

            'product_id.exists' =>
                'Product not found.',

            'type.required' =>
                'Item type is required.',

            'type.in' =>
                'Invalid item type.',

            'name.required' =>
                'Item name is required.',

            'quantity.required' =>
                'Quantity is required.',

            'quantity.min' =>
                'Quantity must be greater than zero.',

            'unit_price.required' =>
                'Unit price is required.',

            'unit_price.min' =>
                'Unit price cannot be negative.',

            'discount.min' =>
                'Discount cannot be negative.',

            'tax.min' =>
                'Tax cannot be negative.',
        ];
    }

    /**
     * Attributes
     */
    public function attributes(): array
    {
        return [

            'invoice_id' => 'invoice',

            'product_id' => 'product',

            'unit_price' => 'unit price',

            'sort_order' => 'sort order',
        ];
    }
}