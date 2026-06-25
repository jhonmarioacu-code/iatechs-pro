<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockTransferRequest extends FormRequest
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

            'product_id' => [
                'required',
                'exists:products,id'
            ],

            'from_branch_id' => [
                'required',
                'exists:branches,id'
            ],

            'to_branch_id' => [
                'required',
                'different:from_branch_id',
                'exists:branches,id'
            ],

            'requested_by' => [
                'required',
                'exists:users,id'
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1'
            ],

            'notes' => [
                'nullable',
                'string'
            ]
        ];
    }
}