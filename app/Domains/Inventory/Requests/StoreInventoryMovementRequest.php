<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInventoryMovementRequest extends FormRequest
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
                'user_id' => $this->input('user_id', $user->id),
            ]);
        }
    }

    public function rules(): array
    {
        $companyId = $this->resolveCompanyId();

        return [
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'branch_id' => ['required', Rule::exists('branches', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'product_id' => ['required', Rule::exists('products', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'user_id' => ['required', Rule::exists('users', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'reference' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:IN,OUT,ADJUSTMENT,TRANSFER_IN,TRANSFER_OUT'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
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
