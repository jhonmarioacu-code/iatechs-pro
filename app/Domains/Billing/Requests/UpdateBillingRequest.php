<?php

declare(strict_types=1);

namespace App\Domains\Billing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBillingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'billings.update'
        ) ?? false;
    }

    public function rules(): array
    {
        return [

            'amount' => [
                'sometimes',
                'numeric',
                'min:0'
            ],

            'currency' => [
                'sometimes',
                'string',
                'max:10'
            ],

            'billing_date' => [
                'sometimes',
                'date'
            ],

            'due_date' => [
                'sometimes',
                'date',
                'after_or_equal:billing_date'
            ],

            'status' => [
                'sometimes',
                Rule::in([
                    'pending',
                    'paid',
                    'failed',
                    'cancelled',
                    'refunded'
                ])
            ],

            'notes' => [
                'nullable',
                'string'
            ],

            'payment_provider' => [
                'nullable',
                'string',
                'max:100'
            ],

            'external_payment_id' => [
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }
}