<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route("id");

        return [
            'name'         => ['required'],
            'phone_number' => ['required', "unique:users,phone_number,$id"],
            'email'        => ['required', "unique:users,email,$id"],
            'role_ids'     => ['nullable', 'array']
        ];
    }
}
