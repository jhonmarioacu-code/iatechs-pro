<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Requests;

use App\Domains\Tickets\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('tickets.update') ?? false;
    }

    public function rules(): array
    {
        /** @var Ticket|null $ticket */
        $ticket = $this->route('ticket');
        $companyId = $ticket?->company_id;

        return [
            'technician_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'status' => [
                'sometimes',
                'in:OPEN,ASSIGNED,IN_DIAGNOSIS,WAITING_QUOTE,APPROVED,IN_REPAIR,READY_DELIVERY,DELIVERED,CLOSED,CANCELLED',
            ],

            'priority' => [
                'sometimes',
                'in:LOW,MEDIUM,HIGH,URGENT',
            ],

            'channel' => [
                'sometimes',
                'in:COUNTER,PHONE,WHATSAPP,EMAIL,WEB',
            ],

            'reported_problem' => [
                'sometimes',
                'string',
                'min:10',
            ],

            'customer_notes' => [
                'nullable',
                'string',
            ],

            'is_warranty' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
