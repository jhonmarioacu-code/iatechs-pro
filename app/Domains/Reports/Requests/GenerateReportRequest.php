<?php

declare(strict_types=1);

namespace App\Domains\Reports\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportRequest extends FormRequest
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

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'type' => [
                'required',
                'in:sales,invoices,payments,tickets,diagnostics,repairs,inventory,purchase_orders,goods_receipts,warranties,customers,technicians,companies'
            ],

            'filters' => [
                'nullable',
                'array'
            ]
        ];
    }
}