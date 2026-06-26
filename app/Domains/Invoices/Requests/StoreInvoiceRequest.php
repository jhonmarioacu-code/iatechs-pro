<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('invoices.create') ?? false;
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

            'customer_id' => [
                'required',
                Rule::exists('customers', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'billing_id' => [
                'nullable',
                Rule::exists('billings', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'ticket_id' => [
                'nullable',
                Rule::exists('tickets', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'repair_id' => [
                'nullable',
                Rule::exists('repairs', 'id')->where(
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

    private function resolveCompanyId(): int
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            return (int) $user->company_id;
        }

        return (int) $this->input('company_id');
    }
}
