<?php

declare(strict_types=1);

namespace App\Domains\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

            'sku' => [
                'nullable',
                'string',
                'max:100'
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'category' => [
                'required',
                'in:PART,ACCESSORY,DEVICE,TOOL,CONSUMABLE,OTHER'
            ],

            'cost_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'sale_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'stock' => [
                'nullable',
                'integer',
                'min:0'
            ],

            'minimum_stock' => [
                'nullable',
                'integer',
                'min:0'
            ],

            'unit' => [
                'nullable',
                'string',
                'max:50'
            ]
        ];
    }
}