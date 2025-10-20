<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
            'student_id' => [
                'nullable',
                'exists:students,id',
                Rule::unique('users', 'student_id')->ignore($this->user->id),
            ],
            'status' => 'required|in:Pending,Aktif,Tidak Aktif',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'photo.image' => 'File foto harus berupa gambar.',
            'role_id.required' => 'Role wajib dipilih.',
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'student_id.unique' => 'Siswa sudah memiliki akun.',
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status harus berupa Pending, Aktif, atau Tidak Aktif.',
        ];
    }
}
        