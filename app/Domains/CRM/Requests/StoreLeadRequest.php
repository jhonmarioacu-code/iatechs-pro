<?php

declare(strict_types=1);

namespace App\Domains\CRM\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'company_id' => [
                'required',
                'exists:companies,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'nullable',
                'email'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50'
            ],

            'source' => [
                'required',
                'in:website,facebook,instagram,whatsapp,google,referral,manual'
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id'
            ]
        ];
    }
}