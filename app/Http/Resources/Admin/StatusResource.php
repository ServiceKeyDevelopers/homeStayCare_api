<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                => $this->id,
            "name"              => $this->name,
            "bg_color"          => $this->bg_color,
            "text_color"        => $this->text_color,
            "slug"              => $this->slug,
            "status"            => $this->status,
            "current_status_id" => $this->current_status_id,
            "created_at"        => $this->created_at,
            "created_by"        => $this->whenLoaded('createdBy')
        ];
    }
}

