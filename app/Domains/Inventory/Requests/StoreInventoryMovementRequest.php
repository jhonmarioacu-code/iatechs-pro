<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
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

            'branch_id' => [
                'required',
                'exists:branches,id'
            ],

            'product_id' => [
                'required',
                'exists:products,id'
            ],

            'user_id' => [
                'required',
                'exists:users,id'
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255'
            ],

            'type' => [
                'required',
                'in:IN,OUT,ADJUSTMENT,TRANSFER_IN,TRANSFER_OUT'
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1'
            ],

            'reason' => [
                'nullable',
                'string'
            ],

            'metadata' => [
                'nullable',
                'array'
            ]
        ];
    }
}