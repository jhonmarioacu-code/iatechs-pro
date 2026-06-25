<?php

declare(strict_types=1);

namespace App\Domains\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'barcode' => [
                'nullable',
                'string',
                'max:255'
            ],

            'name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'category' => [
                'nullable',
                'in:PART,ACCESSORY,DEVICE,TOOL,CONSUMABLE,OTHER'
            ],

            'cost_price' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'sale_price' => [
                'nullable',
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
            ],

            'status' => [
                'nullable',
                'in:ACTIVE,INACTIVE'
            ]
        ];
    }
}