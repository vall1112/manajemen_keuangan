<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'student_id' => $this->student_id ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:50|alpha_dash|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::default()],
            'photo' => 'nullable|image',
            'role_id' => 'required',
            'student_id' => 'nullable|exists:students,id|unique:users,student_id',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique'    => 'Username sudah digunakan.',
            'email.unique'       => 'Email sudah digunakan.',
            'student_id.unique'  => 'Siswa ini sudah memiliki akun pengguna.',
            'student_id.exists'  => 'Siswa tidak ditemukan.',
        ];
    }
}
