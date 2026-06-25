<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'sometimes',
                'exists:products,id',
            ],

            'from_branch_id' => [
                'sometimes',
                'exists:branches,id',
            ],

            'to_branch_id' => [
                'sometimes',
                'different:from_branch_id',
                'exists:branches,id',
            ],

            'quantity' => [
                'sometimes',
                'integer',
                'min:1',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
