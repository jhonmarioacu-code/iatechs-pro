<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosticResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'diagnostic_code' => $this->diagnostic_code,

            'company_id' => $this->company_id,

            'branch_id' => $this->branch_id,

            'ticket_id' => $this->ticket_id,

            'technician_id' => $this->technician_id,

            'status' => $this->status,

            'reported_problem' => $this->reported_problem,

            'diagnostic_result' => $this->diagnostic_result,

            'recommended_solution' => $this->recommended_solution,

            'estimated_cost' => $this->estimated_cost,

            'estimated_hours' => $this->estimated_hours,

            'started_at' => $this->started_at,

            'finished_at' => $this->finished_at,

            'company' => $this->whenLoaded(
                'company'
            ),

            'branch' => $this->whenLoaded(
                'branch'
            ),

            'ticket' => $this->whenLoaded(
                'ticket'
            ),

            'technician' => $this->whenLoaded(
                'technician'
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}