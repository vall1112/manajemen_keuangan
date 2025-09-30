<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
        return [
            'username' => 'required|string|max:50|alpha_dash',
            'name'     => 'required|min:3|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::default()],
            'photo'    => 'nullable|image',
            'role_id'  => 'required|numeric',
            'student_id' => 'nullable|exists:students,id',
            'status'      => 'required|in:Pending,Aktif,Tidak Aktif',
        ];
    }
}
