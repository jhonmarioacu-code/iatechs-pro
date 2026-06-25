<?php

declare(strict_types=1);

namespace App\Domains\Plans\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $planId = $this->route('plan')->id;

        return [

            'name' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('plans', 'name')
                    ->ignore($planId)
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'monthly_price' => [
                'sometimes',
                'numeric',
                'min:0'
            ],

            'yearly_price' => [
                'sometimes',
                'numeric',
                'min:0'
            ],

            'max_users' => [
                'sometimes',
                'integer',
                'min:1'
            ],

            'max_branches' => [
                'sometimes',
                'integer',
                'min:1'
            ],

            'max_storage_gb' => [
                'sometimes',
                'integer',
                'min:1'
            ],

            'max_tickets' => [
                'sometimes',
                'integer',
                'min:1'
            ],

            'ai_requests_limit' => [
                'sometimes',
                'integer',
                'min:0'
            ],

            'trial_days' => [
                'sometimes',
                'integer',
                'min:0'
            ],

            'has_ai' => [
                'nullable',
                'boolean'
            ],

            'has_inventory' => [
                'nullable',
                'boolean'
            ],

            'has_reports' => [
                'nullable',
                'boolean'
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'active',
                    'inactive'
                ])
            ],
        ];
    }
}