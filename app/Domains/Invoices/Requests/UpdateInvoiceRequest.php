<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Requests;

use App\Domains\Invoices\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('invoices.update') ?? false;
    }

    public function rules(): array
    {
        /** @var Invoice|null $invoice */
        $invoice = $this->route('invoice');
        $companyId = $invoice?->company_id;

        return [
            'status' => [
                'nullable',
                'in:draft,issued,partially_paid,paid,overdue,cancelled,refunded',
            ],

            'billing_id' => [
                'nullable',
                Rule::exists('billings', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'subtotal' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'nullable',
                'string',
                'max:10',
            ],

            'exchange_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
