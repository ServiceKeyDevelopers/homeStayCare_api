<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"         => $this->id,
            "title"      => $this->title,
            "type"       => $this->type,
            "contact"    => $this->contact,
            "status"     => $this->status,
            "icon"       => $this->icon,
            "created_at" => $this->created_at,
            "created_by" => $this->createdBy->name ?? null,
        ];
    }
}
