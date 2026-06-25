<?php

declare(strict_types=1);

namespace App\Domains\Devices\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            'customer_id' => $this->customer_id,

            'device_type' => $this->device_type,

            'brand' => $this->brand,

            'model' => $this->model,

            'serial_number' => $this->serial_number,

            'imei' => $this->imei,

            'color' => $this->color,

            'accessories' => $this->accessories,

            'observations' => $this->observations,

            'is_active' => $this->is_active,

            'company' => $this->whenLoaded(
                'company'
            ),

            'branch' => $this->whenLoaded(
                'branch'
            ),

            'customer' => $this->whenLoaded(
                'customer'
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}