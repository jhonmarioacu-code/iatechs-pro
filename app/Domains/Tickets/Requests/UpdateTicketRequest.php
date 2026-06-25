<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'technician_id' => [
                'nullable',
                'exists:users,id'
            ],

            'status' => [
                'sometimes',
                'in:OPEN,ASSIGNED,IN_DIAGNOSIS,WAITING_QUOTE,APPROVED,IN_REPAIR,READY_DELIVERY,DELIVERED,CLOSED,CANCELLED'
            ],

            'priority' => [
                'sometimes',
                'in:LOW,MEDIUM,HIGH,URGENT'
            ],

            'channel' => [
                'sometimes',
                'in:COUNTER,PHONE,WHATSAPP,EMAIL,WEB'
            ],

            'reported_problem' => [
                'sometimes',
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
}