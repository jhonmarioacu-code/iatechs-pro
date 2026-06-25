<?php

declare(strict_types=1);

namespace App\Domains\Quotes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'status' => [
                'nullable',
                'in:DRAFT,PENDING_APPROVAL,APPROVED,REJECTED,EXPIRED,CANCELLED'
            ],

            'subtotal' => [
                'nullable',
                'numeric',
                'min:0'
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

            'expires_at' => [
                'nullable',
                'date'
            ]
        ];
    }
}