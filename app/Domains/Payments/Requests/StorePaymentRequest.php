<?php

declare(strict_types=1);

namespace App\Domains\Payments\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('payments.create') ?? false;
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

            'invoice_id' => [
                'required',
                Rule::exists('invoices', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'customer_id' => [
                'required',
                Rule::exists('customers', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'payment_method' => [
                'required',
                Rule::in([
                    'CASH',
                    'CARD',
                    'BANK_TRANSFER',
                    'PSE',
                    'NEQUI',
                    'DAVIPLATA',
                    'PAYPAL',
                    'STRIPE',
                    'MERCADOPAGO',
                    'OTHER',
                ]),
            ],

            'external_transaction_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'currency' => [
                'nullable',
                'string',
                'max:10',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'is_partial' => [
                'nullable',
                'boolean',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
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
