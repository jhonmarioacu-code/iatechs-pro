<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Requests;

use App\Domains\Repairs\Models\Repair;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRepairRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('repairs.update') ?? false;
    }

    public function rules(): array
    {
        /** @var Repair|null $repair */
        $repair = $this->route('repair');
        $companyId = $repair?->company_id;

        return [
            'technician_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'status' => [
                'nullable',
                'in:PENDING,ASSIGNED,IN_PROGRESS,WAITING_PARTS,COMPLETED,DELIVERED,CANCELLED',
            ],

            'repair_notes' => [
                'nullable',
                'string',
            ],

            'labor_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'parts_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }
}
