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
            "id"         => $this->id,
            "name"       => $this->name,
            "email"      => $this->email,
            "Post_code"  => $this->Post_code,
            "Service_id" => $this->Service_id,
            "status"     => $this->status,
            "created_at" => $this->created_at,
            "created_by" => $this->whenLoaded("createdBy"),
        ];
    }
}
