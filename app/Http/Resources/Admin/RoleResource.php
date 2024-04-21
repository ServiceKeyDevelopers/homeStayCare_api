<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"           => $this->id,
            "display_name" => $this->display_name,
            "name"         => $this->name,
            "description"  => $this->description,
            "created_at"   => $this->created_at,
            "created_by"   => $this->whenLoaded("createdBy"),
            "permissions"  => $this->whenLoaded("permissions"),
        ];
    }
}
