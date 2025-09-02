<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'nama' => 'Adi Zulfa',
            'nis' => '2025001',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2008-03-12',
            'alamat' => 'Jl. Setro No. 5, Gresik',
            'classroom_id' => 3,
            'email' => 'adisetro@gmail.com',
            'telepon' => '081234567891',
            'status' => 'aktif',
            'nama_ayah' => 'Sutrisno,M.Pd,',
            'telepon_ayah' => '081234567892',
            'nama_ibu' => 'Izatul, S.E.',
            'telepon_ibu' => '081234567893',
            'foto' => null,
        ]);

        Student::create([
            'nama' => 'Raditiya Surya',
            'nis' => '2025002',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2009-07-22',
            'alamat' => 'Jl. Mboro No. 8, Surabaya',
            'classroom_id' => 2,
            'email' => 'surya@gmail.com',
            'telepon' => '089876543210',
            'status' => 'aktif',
            'nama_ayah' => 'Hendro',
            'telepon_ayah' => '089812345678',
            'nama_ibu' => 'Wati',
            'telepon_ibu' => '089823456789',
            'foto' => null,
        ]);
    }
}
