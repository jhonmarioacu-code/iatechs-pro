<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'reference' => [
                'nullable',
                'string',
                'max:255'
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