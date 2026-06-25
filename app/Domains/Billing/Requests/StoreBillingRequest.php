<?php

declare(strict_types=1);

namespace App\Domains\Billing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBillingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'billings.create'
        ) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if (
            $user &&
            !$user->hasRole('super_admin')
        ) {

            $this->merge([
                'company_id' => $user->company_id
            ]);
        }
    }

    public function rules(): array
    {
        return [

            'company_id' => [
                'required',
                Rule::exists(
                    'companies',
                    'id'
                )
            ],

            'subscription_id' => [
                'required',
                Rule::exists(
                    'subscriptions',
                    'id'
                )
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'currency' => [
                'required',
                'string',
                'max:10'
            ],

            'billing_date' => [
                'required',
                'date'
            ],

            'due_date' => [
                'required',
                'date',
                'after_or_equal:billing_date'
            ],

            'notes' => [
                'nullable',
                'string'
            ],
        ];
    }
}