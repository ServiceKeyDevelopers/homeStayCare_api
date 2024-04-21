<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "day"         => $this->day,
            "early"       => $this->early,
            "late"        => $this->late,
            "night"       => $this->night,
            "unavailable" => $this->unavailable
        ];
    }
}
