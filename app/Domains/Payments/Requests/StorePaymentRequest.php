<?php

declare(strict_types=1);

namespace App\Domains\Payments\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'branch_id' => [

                'required',

                'exists:branches,id'
            ],

            'invoice_id' => [

                'required',

                'exists:invoices,id'
            ],

            'customer_id' => [

                'required',

                'exists:customers,id'
            ],

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment_method' => [

                'required',
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

                'max:255'
            ],

            'reference' => [

                'nullable',

                'string',

                'max:255'
            ],

            'currency' => [

                'nullable',

                'string',

                'max:10'
            ],

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount' => [

                'required',

                'numeric',

                'min:0.01'
            ],

            'is_partial' => [

                'nullable',

                'boolean'
            ],

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            'notes' => [

                'nullable',

                'string',

                'max:5000'
            ]
        ];
    }
}
