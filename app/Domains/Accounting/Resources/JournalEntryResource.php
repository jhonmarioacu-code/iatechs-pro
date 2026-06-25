<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'company_id' => $this->company_id,

            'entry_number' => $this->entry_number,

            'entry_date' => $this->entry_date,

            'description' => $this->description,

            'status' => $this->status,

            'created_by' => $this->created_by,

            'lines' => $this->whenLoaded('lines'),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}