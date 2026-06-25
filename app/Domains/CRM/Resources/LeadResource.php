<?php

declare(strict_types=1);

namespace App\Domains\CRM\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'name' => $this->name,

            'email' => $this->email,

            'phone' => $this->phone,

            'source' => $this->source,

            'status' => $this->status,

            'assigned_to' => $this->assigned_to,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}