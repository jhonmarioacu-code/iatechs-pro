<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStockTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('inventory.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            $this->merge([
                'company_id' => $user->company_id,
                'requested_by' => $this->input('requested_by', $user->id),
            ]);
        }
    }

    public function rules(): array
    {
        $companyId = $this->resolveCompanyId();

        return [
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'product_id' => ['required', Rule::exists('products', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'from_branch_id' => ['required', Rule::exists('branches', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'to_branch_id' => ['required', 'different:from_branch_id', Rule::exists('branches', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'requested_by' => ['required', Rule::exists('users', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
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
