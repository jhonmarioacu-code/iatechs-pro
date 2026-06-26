<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('purchase-orders.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'notes' => ['nullable', 'string'],
            'tax' => ['nullable', 'numeric'],
            'discount' => ['nullable', 'numeric'],
        ];
    }
}
