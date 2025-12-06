<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => 'Aktif',
            'password' => bcrypt('12345678'),
            'photo' => null,
            'teacher_id' => null,
            'student_id' => null,
        ])->assignRole('admin');
    }
}
