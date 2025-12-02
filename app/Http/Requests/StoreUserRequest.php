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
            'username'    => 'required|string|max:50|alpha_dash|unique:users',
            'teacher_id'  => 'nullable|exists:teachers,id|unique:users,teacher_id',
            'student_id'  => 'nullable|exists:students,id|unique:users,student_id',
            'name'        => 'required',
            'email'       => 'required|email|unique:users',
            'password'    => ['required', 'confirmed', Password::default()],
            'photo'       => 'nullable|image',
            'role_id'     => 'required',
            'status'      => 'required|in:Pending,Aktif,Tidak Aktif',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique'    => 'Username sudah digunakan.',
            'teacher_id.unique'  => 'Guru ini sudah memiliki akun pengguna.',
            'teacher_id.exists'  => 'Guru tidak ditemukan.',
            'student_id.unique'  => 'Siswa ini sudah memiliki akun pengguna.',
            'student_id.exists'  => 'Siswa tidak ditemukan.',
            'email.unique'       => 'Email sudah digunakan.',
            'status.required'    => 'Status harus dipilih.',
            'status.in'          => 'Status hanya boleh aktif, pending, atau tidak aktif.',
        ];
    }
}
