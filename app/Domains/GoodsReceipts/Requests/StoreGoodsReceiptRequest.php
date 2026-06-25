<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoodsReceiptRequest extends FormRequest
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

            'purchase_order_id' => [
                'required',
                'exists:purchase_orders,id'
            ],

            'supplier_id' => [
                'required',
                'exists:suppliers,id'
            ],

            'received_by' => [
                'required',
                'exists:users,id'
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

            'items.*.ordered_quantity' => [
                'required',
                'integer',
                'min:1'
            ],

            'items.*.received_quantity' => [
                'required',
                'integer',
                'min:1'
            ],

            'items.*.pending_quantity' => [
                'required',
                'integer',
                'min:0'
            ],

            'items.*.unit_cost' => [
                'required',
                'numeric'
            ],

            'items.*.subtotal' => [
                'required',
                'numeric'
            ]
        ];
    }
}