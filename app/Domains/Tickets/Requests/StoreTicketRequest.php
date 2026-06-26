<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('tickets.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            $this->merge([
                'company_id' => $user->company_id,
            ]);
        }
    }

    public function rules(): array
    {
        $companyId = $this->resolveCompanyId();

        return [
            'company_id' => [
                'required',
                Rule::exists('companies', 'id'),
            ],

            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'customer_id' => [
                'required',
                Rule::exists('customers', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'device_id' => [
                'required',
                Rule::exists('devices', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'technician_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'priority' => [
                'nullable',
                'in:LOW,MEDIUM,HIGH,URGENT',
            ],

            'channel' => [
                'nullable',
                'in:COUNTER,PHONE,WHATSAPP,EMAIL,WEB',
            ],

            'reported_problem' => [
                'required',
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
                'La descripcion de la falla debe contener al menos 10 caracteres.',
        ];
    }

    private function resolveCompanyId(): int
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            return (int) $user->company_id;
        }

        return (int) $this->input('company_id');
    }
}
