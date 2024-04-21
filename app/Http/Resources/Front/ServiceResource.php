<?php

namespace App\Http\Resources\Front;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                  => $this->id,
            "title"               => $this->title,
            "short_description_1" => $this->short_description_1,
            "short_description_2" => $this->short_description_2,
            "description"         => $this->description,
            "banner_image"        => $this->banner_image,
            "first_image"         => $this->first_image,
            "second_image"        => $this->second_image,
        ];
    }
}
