<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'subscriptions.create'
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

            'plan_id' => [
                'required',
                Rule::exists(
                    'plans',
                    'id'
                )->where(
                    'status',
                    'active'
                )
            ],

            'billing_cycle' => [
                'required',
                Rule::in([
                    'monthly',
                    'yearly'
                ])
            ],

            'amount' => [
                'nullable',
                'numeric',
                'min:0'
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
        ];
    }
}