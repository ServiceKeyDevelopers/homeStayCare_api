<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "post_code"        => $this->post_code,
            "nid_number"       => $this->nid_number,
            "passport_type"    => $this->passport_type,
            "passport_number"  => $this->passport_number,
            "passport"         => $this->passport,
            "address"          => $this->address,
            "address_line_1"   => $this->address_line_1,
            "address_line_2"   => $this->address_line_2,
            "kin_first_name"   => $this->kin_first_name,
            "kin_middle_name"  => $this->kin_middle_name,
            "kin_last_name"    => $this->kin_last_name,
            "kin_phone_number" => $this->kin_phone_number,
            "country"          => CountryResource::make($this->whenLoaded("country")),
            "city"             => CityResource::make($this->whenLoaded("city"))
        ];
    }
}
