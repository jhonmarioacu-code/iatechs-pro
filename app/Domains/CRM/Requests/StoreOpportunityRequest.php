<?php

declare(strict_types=1);

namespace App\Domains\CRM\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOpportunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'lead_id' => [
                'required',
                'exists:crm_leads,id'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'amount' => [
                'nullable',
                'numeric'
            ],

            'stage' => [
                'nullable',
                'in:prospecting,qualification,proposal,negotiation,won,lost'
            ],

            'expected_close_date' => [
                'nullable',
                'date'
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id'
            ]
        ];
    }
}