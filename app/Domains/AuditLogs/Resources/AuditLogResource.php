<?php

declare(strict_types=1);

namespace App\Domains\AuditLogs\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'user_id' => $this->user_id,

            'event' => $this->event,

            'module' => $this->module,

            'entity_type' => $this->entity_type,

            'entity_id' => $this->entity_id,

            'old_values' => $this->old_values,

            'new_values' => $this->new_values,

            'ip_address' => $this->ip_address,

            'user_agent' => $this->user_agent,

            'url' => $this->url,

            'method' => $this->method,

            'occurred_at' => $this->occurred_at,

            'company' => $this->whenLoaded(
                'company'
            ),

            'user' => $this->whenLoaded(
                'user'
            ),

            'created_at' => $this->created_at,
        ];
    }
}