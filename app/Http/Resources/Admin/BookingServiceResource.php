<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"             => $this->id,
            "name"           => $this->name,
            "email"          => $this->email,
            "post_code"      => $this->post_code,
            "status"         => $this->status,
            "created_at"     => $this->created_at,
            "service"        => ServiceResource::make($this->whenLoaded('service')),
            "current_status" => StatusResource::make($this->whenLoaded('currentStatus')),
            "created_by"     => $this->whenLoaded("createdBy"),
        ];
    }
}
