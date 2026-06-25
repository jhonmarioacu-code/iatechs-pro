<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiagnosticRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'technician_id' => [
                'nullable',
                'exists:users,id'
            ],

            'status' => [
                'nullable',
                'in:PENDING,IN_PROGRESS,COMPLETED,REQUIRES_PARTS,NOT_REPAIRABLE,CANCELLED'
            ],

            'diagnostic_result' => [
                'nullable',
                'string'
            ],

            'recommended_solution' => [
                'nullable',
                'string'
            ],

            'estimated_cost' => [
                'nullable',
                'numeric'
            ],

            'estimated_hours' => [
                'nullable',
                'integer'
            ]
        ];
    }
}