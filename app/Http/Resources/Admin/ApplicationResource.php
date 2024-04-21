<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
// use  App\Http\Resources\Admin\ServiceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            "first_name"     => $this->first_name,
            "middle_name"    => $this->middle_name,
            "last_name"      => $this->last_name,
            "email"          => $this->email,
            "phone_number"   => $this->phone_number,
            "reference_name" => $this->reference_name,
            "date_of_birth"  => $this->date_of_birth,
            "image"          => $this->image,
            "cv"             => $this->cv,
            "created_at"     => $this->created_at,
            "created_by"     => $this->whenLoaded("createdBy"),
            "address"        => AddressResource::make($this->whenLoaded("address")),
            "schedules"      => ScheduleCollection::make($this->whenLoaded("schedules")),
        ];
    }
}
