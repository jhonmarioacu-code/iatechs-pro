<?php

declare(strict_types=1);

namespace App\Domains\Quotes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
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

            'ticket_id' => [
                'required',
                'exists:tickets,id'
            ],

            'diagnostic_id' => [
                'required',
                'exists:diagnostics,id'
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
                'date',
                'after:today'
            ]
        ];
    }
}