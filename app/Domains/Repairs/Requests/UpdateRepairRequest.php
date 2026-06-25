<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRepairRequest extends FormRequest
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
                'nullable',
                'in:PENDING,ASSIGNED,IN_PROGRESS,WAITING_PARTS,COMPLETED,DELIVERED,CANCELLED'
            ],

            'repair_notes' => [
                'nullable',
                'string'
            ],

            'labor_cost' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'parts_cost' => [
                'nullable',
                'numeric',
                'min:0'
            ]
        ];
    }
}