<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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

            'code' => [
                'required',
                'string',
                'max:50'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'type' => [
                'required',
                'in:asset,liability,equity,income,expense'
            ],

            'parent_id' => [
                'nullable',
                'exists:accounts,id'
            ]
        ];
    }
}