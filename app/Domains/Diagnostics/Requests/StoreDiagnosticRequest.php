<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiagnosticRequest extends FormRequest
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

            'technician_id' => [
                'nullable',
                'exists:users,id'
            ],

            'reported_problem' => [
                'required',
                'string',
                'min:10'
            ]
        ];
    }
}