<?php

declare(strict_types=1);

namespace App\Domains\Companies\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'name' => $this->name,
            'slug' => $this->slug,

            'legal_name' => $this->legal_name,
            'tax_id' => $this->tax_id,

            'email' => $this->email,
            'phone' => $this->phone,

            'website' => $this->website,

            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,

            'logo' => $this->logo,

            'status' => $this->status,

            'trial_ends_at' => $this->trial_ends_at,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}