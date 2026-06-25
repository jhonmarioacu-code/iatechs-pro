<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'invoices.update'
        ) ?? false;
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [

                'nullable',

                'in:draft,issued,partially_paid,paid,overdue,cancelled,refunded'
            ],

            /*
            |--------------------------------------------------------------------------
            | Billing
            |--------------------------------------------------------------------------
            */

            'billing_id' => [

                'nullable',

                'exists:billings,id'
            ],

            /*
            |--------------------------------------------------------------------------
            | Financial
            |--------------------------------------------------------------------------
            */

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

            'currency' => [

                'nullable',
                'string',
                'max:10'
            ],

            'exchange_rate' => [

                'nullable',
                'numeric',
                'min:0'
            ],

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'due_date' => [

                'nullable',
                'date'
            ],

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            'notes' => [

                'nullable',
                'string'
            ]
        ];
    }
}