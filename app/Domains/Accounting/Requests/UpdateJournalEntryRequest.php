<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJournalEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'description' => [
                'nullable',
                'string'
            ],

            'entry_date' => [
                'sometimes',
                'date'
            ]
        ];
    }
}