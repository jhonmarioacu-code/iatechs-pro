<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
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

            'supplier_id' => [
                'required',
                'exists:suppliers,id'
            ],

            'created_by' => [
                'required',
                'exists:users,id'
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'notes' => [
                'nullable',
                'string'
            ],

            'items' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id'
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],

            'items.*.unit_cost' => [
                'required',
                'numeric',
                'min:0'
            ]
        ];
    }
}