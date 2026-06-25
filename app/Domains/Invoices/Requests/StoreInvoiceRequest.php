<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'invoices.create'
        ) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        /*
        |--------------------------------------------------------------------------
        | Multi Tenant
        |--------------------------------------------------------------------------
        */

        if (
            $user &&
            !$user->hasRole('super_admin')
        ) {

            $this->merge([
                'company_id' => $user->company_id
            ]);
        }
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Tenant
            |--------------------------------------------------------------------------
            */

            'company_id' => [

                'required',

                Rule::exists(
                    'companies',
                    'id'
                )
            ],

            'branch_id' => [

                'required',

                Rule::exists(
                    'branches',
                    'id'
                )
            ],

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            'customer_id' => [

                'required',

                Rule::exists(
                    'customers',
                    'id'
                )
            ],

            /*
            |--------------------------------------------------------------------------
            | Billing
            |--------------------------------------------------------------------------
            */

            'billing_id' => [

                'nullable',

                Rule::exists(
                    'billings',
                    'id'
                )
            ],

            /*
            |--------------------------------------------------------------------------
            | Repair Center
            |--------------------------------------------------------------------------
            */

            'ticket_id' => [

                'nullable',

                Rule::exists(
                    'tickets',
                    'id'
                )
            ],

            'repair_id' => [

                'nullable',

                Rule::exists(
                    'repairs',
                    'id'
                )
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