<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            "title"               => ["required", "string"],
            "banner_image"        => ["required", "image", "mimes:jpeg,png,jpg,gif,webp,svg"],
            "first_image"         => ["required", "image", "mimes:jpeg,png,jpg,gif,webp,svg"],
            "short_description_1" => ["required", "string"],
            "description"         => ["required", "string"]
        ];
    }
}
