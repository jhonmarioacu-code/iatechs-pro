<?php

declare(strict_types=1);

namespace App\Domains\Devices\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Domains\Devices\Models\Device;

class UpdateDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $device = $this->route('device');

        return $device instanceof Device
            && ($this->user()?->can('update', $device) ?? false);
    }

    public function rules(): array
    {
        return [

            'device_type' => [
                'sometimes',
                'string',
                'max:100'
            ],

            'brand' => [
                'sometimes',
                'string',
                'max:100'
            ],

            'model' => [
                'sometimes',
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
}
