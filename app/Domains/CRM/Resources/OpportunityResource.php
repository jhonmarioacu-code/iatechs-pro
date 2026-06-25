<?php

declare(strict_types=1);

namespace App\Domains\CRM\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'lead_id' => $this->lead_id,

            'title' => $this->title,

            'amount' => $this->amount,

            'stage' => $this->stage,

            'expected_close_date' => $this->expected_close_date,

            'assigned_to' => $this->assigned_to,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}