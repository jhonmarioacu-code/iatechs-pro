<?php

declare(strict_types=1);

namespace App\Domains\Plans\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:100',
                'unique:plans,name'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'monthly_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'yearly_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'max_users' => [
                'required',
                'integer',
                'min:1'
            ],

            'max_branches' => [
                'required',
                'integer',
                'min:1'
            ],

            'max_storage_gb' => [
                'required',
                'integer',
                'min:1'
            ],

            'max_tickets' => [
                'required',
                'integer',
                'min:1'
            ],

            'ai_requests_limit' => [
                'required',
                'integer',
                'min:0'
            ],

            'trial_days' => [
                'required',
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