<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoodsReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'notes' => [
                'nullable',
                'string'
            ]
        ];
    }
}