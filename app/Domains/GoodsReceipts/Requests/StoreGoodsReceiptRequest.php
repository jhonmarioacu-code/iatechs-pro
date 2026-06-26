<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGoodsReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('goods-receipts.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            $this->merge([
                'company_id' => $user->company_id,
                'received_by' => $this->input('received_by', $user->id),
            ]);
        }
    }

    public function rules(): array
    {
        $companyId = $this->resolveCompanyId();

        return [
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'purchase_order_id' => ['required', Rule::exists('purchase_orders', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'supplier_id' => ['required', Rule::exists('suppliers', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'received_by' => ['required', Rule::exists('users', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', Rule::exists('products', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'items.*.ordered_quantity' => ['required', 'integer', 'min:1'],
            'items.*.received_quantity' => ['required', 'integer', 'min:1'],
            'items.*.pending_quantity' => ['required', 'integer', 'min:0'],
            'items.*.unit_cost' => ['required', 'numeric'],
            'items.*.subtotal' => ['required', 'numeric'],
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
