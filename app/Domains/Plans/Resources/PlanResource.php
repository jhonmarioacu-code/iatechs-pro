<?php

declare(strict_types=1);

namespace App\Domains\Plans\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'name' => $this->name,
            'slug' => $this->slug,

            'description' => $this->description,

            'monthly_price' => $this->monthly_price,
            'yearly_price' => $this->yearly_price,

            'max_users' => $this->max_users,
            'max_branches' => $this->max_branches,
            'max_storage_gb' => $this->max_storage_gb,

            'max_tickets' => $this->max_tickets,

            'ai_requests_limit' => $this->ai_requests_limit,

            'trial_days' => $this->trial_days,

            'has_ai' => $this->has_ai,
            'has_inventory' => $this->has_inventory,
            'has_reports' => $this->has_reports,

            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}