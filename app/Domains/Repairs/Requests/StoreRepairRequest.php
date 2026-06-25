<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRepairRequest extends FormRequest
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

            'quote_id' => [
                'required',
                'exists:quotes,id'
            ],

            'technician_id' => [
                'nullable',
                'exists:users,id'
            ],

            'repair_notes' => [
                'nullable',
                'string'
            ],

            'labor_cost' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'parts_cost' => [
                'nullable',
                'numeric',
                'min:0'
            ]
        ];
    }
}