<?php

declare(strict_types=1);

namespace App\Domains\Compliance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateComplianceRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('compliance.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['sometimes', 'nullable', 'integer', 'exists:branches,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'nullable', 'string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'status' => ['sometimes', 'string', Rule::in(['draft', 'active', 'inactive', 'archived'])],
            'starts_at' => ['sometimes', 'nullable', 'date'],
            'ends_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:starts_at'],
            'metadata' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
