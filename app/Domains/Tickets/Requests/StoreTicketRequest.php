<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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

            'branch_id' => [
                'required',
                'exists:branches,id'
            ],

            'customer_id' => [
                'required',
                'exists:customers,id'
            ],

            'device_id' => [
                'required',
                'exists:devices,id'
            ],

            'technician_id' => [
                'nullable',
                'exists:users,id'
            ],

            'priority' => [
                'nullable',
                'in:LOW,MEDIUM,HIGH,URGENT'
            ],

            'channel' => [
                'nullable',
                'in:COUNTER,PHONE,WHATSAPP,EMAIL,WEB'
            ],

            'reported_problem' => [
                'required',
                'string',
                'min:10'
            ],

            'customer_notes' => [
                'nullable',
                'string'
            ],

            'is_warranty' => [
                'nullable',
                'boolean'
            ]
        ];
    }

    public function messages(): array
    {
        return [

            'company_id.required' =>
                'La empresa es obligatoria.',

            'branch_id.required' =>
                'La sucursal es obligatoria.',

            'customer_id.required' =>
                'El cliente es obligatorio.',

            'device_id.required' =>
                'El dispositivo es obligatorio.',

            'reported_problem.required' =>
                'Debe indicar la falla reportada.',

            'reported_problem.min' =>
                'La descripción de la falla debe contener al menos 10 caracteres.',
        ];
    }
}