<?php

declare(strict_types=1);

namespace App\Domains\Branches\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'name' => $this->name,

            'code' => $this->code,

            'phone' => $this->phone,

            'email' => $this->email,

            'manager_name' => $this->manager_name,

            'address' => $this->address,

            'city' => $this->city,

            'state' => $this->state,

            'country' => $this->country,

            'is_main' => $this->is_main,

            'is_active' => $this->is_active,

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            'company' => $this->whenLoaded(
                'company'
            ),

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),

            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}