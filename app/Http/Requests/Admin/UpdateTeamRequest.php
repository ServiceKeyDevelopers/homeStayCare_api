<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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
            "name"        => ["required", "string", "max:50"],
            "designation" => ["required", "string", "max:50"],
            "description" => ["required", "string", "max:500"],
            "image"       => ["sometimes", "nullable", "image", "mimes:jpeg,png,jpg,gif,webp,svg"]
        ];
    }
}
