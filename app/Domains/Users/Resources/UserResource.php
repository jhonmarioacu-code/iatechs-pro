<?php

declare(strict_types=1);

namespace App\Domains\Users\Resources;

use App\Domains\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'name' => $this->name,

            'email' => $this->email,

            'phone' => $this->phone,

            'company_id' => $this->company_id,

            'roles' => $this->resource instanceof User
                ? $this->resource->getRoleNames()
                : [],

            'is_active' => $this->is_active
        ];
    }
}
