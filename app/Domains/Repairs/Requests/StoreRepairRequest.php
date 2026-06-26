<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRepairRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('repairs.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            $this->merge([
                'company_id' => $user->company_id,
            ]);
        }
    }

    public function rules(): array
    {
        $companyId = $this->resolveCompanyId();

        return [
            'company_id' => [
                'required',
                Rule::exists('companies', 'id'),
            ],

            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'ticket_id' => [
                'required',
                Rule::exists('tickets', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'diagnostic_id' => [
                'required',
                Rule::exists('diagnostics', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'quote_id' => [
                'required',
                Rule::exists('quotes', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'technician_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'repair_notes' => [
                'nullable',
                'string',
            ],

            'labor_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'parts_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }

    private function resolveCompanyId(): int
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            return (int) $user->company_id;
        }

        return (int) $this->input('company_id');
    }
}
