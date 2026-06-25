<?php

declare(strict_types=1);

namespace App\Domains\Warranties\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarrantyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'status' => [
                'nullable',
                'in:ACTIVE,EXPIRED,VOID,CLAIMED'
            ],

            'type' => [
                'nullable',
                'in:REPAIR,PARTS,PRODUCT,EXTENDED'
            ],

            'start_date' => [
                'nullable',
                'date'
            ],

            'end_date' => [
                'nullable',
                'date'
            ],

            'terms' => [
                'nullable',
                'string'
            ],

            'notes' => [
                'nullable',
                'string'
            ]
        ];
    }
}