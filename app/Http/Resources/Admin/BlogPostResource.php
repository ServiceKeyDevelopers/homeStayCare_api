<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"               => $this->id,
            "title"            => $this->title,
            "status"           => $this->status,
            "description"      => $this->description,
            "image"            => $this->image,
            "meta_title"       => $this->meta_title,
            "meta_tag"         => $this->meta_tag,
            "meta_description" => $this->meta_description,
            "created_at"       => $this->created_at,
            "category"         => $this->whenLoaded("category"),
            "created_by"       => $this->whenLoaded("createdBy"),
            "tags"             => $this->whenLoaded("tags"),
        ];
    }
}
