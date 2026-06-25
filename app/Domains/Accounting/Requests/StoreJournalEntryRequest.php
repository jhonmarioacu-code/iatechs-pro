<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJournalEntryRequest extends FormRequest
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

            'entry_number' => [
                'required',
                'string',
                'max:100'
            ],

            'entry_date' => [
                'required',
                'date'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'created_by' => [
                'nullable',
                'exists:users,id'
            ],

            'lines' => [
                'required',
                'array',
                'min:2'
            ],

            'lines.*.account_id' => [
                'required',
                'exists:accounts,id'
            ],

            'lines.*.debit' => [
                'nullable',
                'numeric'
            ],

            'lines.*.credit' => [
                'nullable',
                'numeric'
            ],

            'lines.*.description' => [
                'nullable',
                'string'
            ]
        ];
    }
}