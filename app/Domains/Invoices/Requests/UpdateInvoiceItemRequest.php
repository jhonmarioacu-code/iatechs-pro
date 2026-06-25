<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceItemRequest extends FormRequest
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        return $this->user()?->can(
            'invoices.update'
        ) ?? false;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'product_id' => [
                'sometimes',
                'nullable',
                Rule::exists(
                    'products',
                    'id'
                ),
            ],

            'type' => [
                'sometimes',
                Rule::in([
                    'product',
                    'service',
                    'repair',
                    'part',
                ]),
            ],

            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'quantity' => [
                'sometimes',
                'numeric',
                'min:0.01',
            ],

            'unit_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            'sort_order' => [
                'sometimes',
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

            'product_id.exists' =>
                'Product not found.',

            'type.in' =>
                'Invalid item type.',

            'quantity.min' =>
                'Quantity must be greater than zero.',

            'unit_price.min' =>
                'Unit price cannot be negative.',

            'discount.min' =>
                'Discount cannot be negative.',

            'tax.min' =>
                'Tax cannot be negative.',
        ];
    }

    /**
     * Custom Attributes
     */
    public function attributes(): array
    {
        return [

            'product_id' => 'product',

            'unit_price' => 'unit price',

            'sort_order' => 'sort order',
        ];
    }
}