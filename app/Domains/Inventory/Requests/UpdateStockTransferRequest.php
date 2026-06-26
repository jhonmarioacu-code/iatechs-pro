<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests;

use App\Domains\Inventory\Models\StockTransfer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStockTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('inventory.update') ?? false;
    }

    public function rules(): array
    {
        /** @var StockTransfer|null $stockTransfer */
        $stockTransfer = $this->route('stockTransfer');
        $companyId = $stockTransfer?->company_id;

        return [
            'product_id' => ['sometimes', Rule::exists('products', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'from_branch_id' => ['sometimes', Rule::exists('branches', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'to_branch_id' => ['sometimes', 'different:from_branch_id', Rule::exists('branches', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
