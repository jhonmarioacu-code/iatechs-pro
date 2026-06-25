<?php

declare(strict_types=1);

namespace App\Domains\Reports\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'user_id' => $this->user_id,

            'name' => $this->name,

            'type' => $this->type,

            'filters' => $this->filters,

            'total_records' => $this->total_records,

            'status' => $this->status,

            'generated_at' => $this->generated_at,

            'exports' => $this->whenLoaded(
                'exports'
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}