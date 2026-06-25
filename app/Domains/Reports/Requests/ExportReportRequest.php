<?php

declare(strict_types=1);

namespace App\Domains\Reports\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'format' => [
                'required',
                'in:PDF,XLSX,CSV,JSON'
            ]
        ];
    }
}