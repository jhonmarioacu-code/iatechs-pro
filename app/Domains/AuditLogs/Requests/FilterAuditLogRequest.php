<?php

declare(strict_types=1);

namespace App\Domains\AuditLogs\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterAuditLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'module' => [
                'nullable',
                'string'
            ],

            'event' => [
                'nullable',
                'string'
            ],

            'user_id' => [
                'nullable',
                'integer'
            ],

            'entity_type' => [
                'nullable',
                'string'
            ],

            'from' => [
                'nullable',
                'date'
            ],

            'to' => [
                'nullable',
                'date'
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100'
            ]
        ];
    }
}