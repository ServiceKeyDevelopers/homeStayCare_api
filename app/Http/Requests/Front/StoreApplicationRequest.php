<?php

namespace App\Http\Requests\Front;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "designation_id"          => ["required", "integer", Rule::exists('designations', 'id')],
            "country_id"              => ["required", "integer", Rule::exists('countries', 'id')],
            "city_id"                 => ["required", "integer", Rule::exists('cities', 'id')],
            "first_name"              => ["required", "string"],
            "middle_name"             => ["nullable", "string"],
            "last_name"               => ["nullable", "string"],
            "date_of_birth"           => ["required", "date"],
            "reference_name"          => ["required", "string"],
            "image"                   => ["required", "image", "mimes:jpeg,png,jpg,webp"],
            "email"                   => ["required", "email"],
            "phone_number"            => ["required"],
            "address_line_1"          => ["required", "string"],
            "nid_number"              => ["required", "string"],
            "passport_type"           => ["required", "string"],
            "passport_number"         => ["required", "string"],
            "passport"                => ["required", "image", "mimes:jpeg,png,jpg,webp"],
            "address_line_2"          => ["required", "string"],
            "kin_first_name"          => ["required", "string"],
            "kin_middle_name"         => ["required", "string"],
            "kin_last_name"           => ["required", "string"],
            "kin_phone_number"        => ["required", "string"],
            "cv"                      => ["required", "mimes:pdf,doc,docx"],
            "schedules"               => ['required', "array"],
            "schedules.*.day"         => ['required', "string"],
            "schedules.*.early"       => ['required', "boolean"],
            "schedules.*.late"        => ['required', "boolean"],
            "schedules.*.night"       => ['required', "boolean"],
            "schedules.*.unavailable" => ['required', "boolean"],
        ];
    }
}
