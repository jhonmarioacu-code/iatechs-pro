<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Requests;

use App\Domains\Diagnostics\Models\Diagnostic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiagnosticRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('diagnostics.update') ?? false;
    }

    public function rules(): array
    {
        /** @var Diagnostic|null $diagnostic */
        $diagnostic = $this->route('diagnostic');
        $companyId = $diagnostic?->company_id;

        return [
            'technician_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'status' => [
                'nullable',
                'in:PENDING,IN_PROGRESS,COMPLETED,REQUIRES_PARTS,NOT_REPAIRABLE,CANCELLED',
            ],

            'diagnostic_result' => ['nullable', 'string'],
            'recommended_solution' => ['nullable', 'string'],
            'estimated_cost' => ['nullable', 'numeric'],
            'estimated_hours' => ['nullable', 'integer'],
        ];
    }
}
