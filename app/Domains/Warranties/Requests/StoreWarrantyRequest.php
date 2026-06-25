<?php

declare(strict_types=1);

namespace App\Domains\Warranties\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarrantyRequest extends FormRequest
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

            'customer_id' => [
                'required',
                'exists:customers,id'
            ],

            'device_id' => [
                'nullable',
                'exists:devices,id'
            ],

            'repair_id' => [
                'nullable',
                'exists:repairs,id'
            ],

            'invoice_id' => [
                'nullable',
                'exists:invoices,id'
            ],

            'type' => [
                'required',
                'in:REPAIR,PARTS,PRODUCT,EXTENDED'
            ],

            'start_date' => [
                'required',
                'date'
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date'
            ],

            'terms' => [
                'nullable',
                'string'
            ],

            'notes' => [
                'nullable',
                'string'
            ]
        ];
    }
}