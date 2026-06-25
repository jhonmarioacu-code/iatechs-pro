<?php

declare(strict_types=1);

namespace App\Domains\Devices\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Domains\Devices\Models\Device;

class StoreDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'create',
            Device::class
        ) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user && !$user->hasRole('super_admin')) {
            $this->merge([
                'company_id' => $user->company_id
            ]);
        }
    }

    public function rules(): array
    {
        return [

            'company_id' => [
                'required',
                Rule::exists('companies', 'id')
            ],

            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')
                    ->where('company_id', $this->input('company_id'))
            ],

            'customer_id' => [
                'required',
                Rule::exists('customers', 'id')
                    ->where('company_id', $this->input('company_id'))
            ],

            'device_type' => [
                'required',
                'string',
                'max:100'
            ],

            'brand' => [
                'required',
                'string',
                'max:100'
            ],

            'model' => [
                'required',
                'string',
                'max:150'
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:255'
            ],

            'imei' => [
                'nullable',
                'string',
                'max:255'
            ],

            'color' => [
                'nullable',
                'string',
                'max:100'
            ],

            'accessories' => [
                'nullable',
                'string'
            ],

            'observations' => [
                'nullable',
                'string'
            ],

            'is_active' => [
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

            'device_type.required' =>
                'El tipo de dispositivo es obligatorio.',

            'brand.required' =>
                'La marca es obligatoria.',

            'model.required' =>
                'El modelo es obligatorio.',
        ];
    }
}
