<?php

declare(strict_types=1);

namespace App\Domains\Payments\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('payments.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'payment_method' => [
                'nullable',
                Rule::in([
                    'CASH',
                    'CARD',
                    'BANK_TRANSFER',
                    'PSE',
                    'NEQUI',
                    'DAVIPLATA',
                    'PAYPAL',
                    'STRIPE',
                    'MERCADOPAGO',
                    'OTHER',
                ]),
            ],

            'external_transaction_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'currency' => [
                'nullable',
                'string',
                'max:10',
            ],

            'amount' => [
                'nullable',
                'numeric',
                'min:0.01',
            ],

            'is_partial' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'PENDING',
                    'COMPLETED',
                    'FAILED',
                    'REFUNDED',
                    'CANCELLED',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }
}
