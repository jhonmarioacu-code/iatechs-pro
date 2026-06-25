<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'subscriptions.update'
        ) ?? false;
    }

    public function rules(): array
    {
        return [

            'plan_id' => [
                'sometimes',
                Rule::exists(
                    'plans',
                    'id'
                )
            ],

            'billing_cycle' => [
                'sometimes',
                Rule::in([
                    'monthly',
                    'yearly'
                ])
            ],

            'starts_at' => [
                'nullable',
                'date'
            ],

            'ends_at' => [
                'nullable',
                'date',
                'after:starts_at'
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'trial',
                    'active',
                    'past_due',
                    'cancelled',
                    'expired'
                ])
            ],
        ];
    }
}