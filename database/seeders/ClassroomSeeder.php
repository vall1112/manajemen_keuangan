<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        // buat kelas
        Classroom::create([
            'nama_kelas' => 'X TITL',
            'jurusan' => 'TEKNIK INSTALASI TENAGA LISTRIK',
            'wali_kelas_id' => 2, // sesuaikan dengan id teacher yang ada
            'jumlah_anak' => 20,
        ]);

        Classroom::create([
            'nama_kelas' => 'XI TKR',
            'jurusan' => 'TEKNIK KENDARAAN RINGAN',
            'wali_kelas_id' => 3, // sesuaikan dengan id teacher yang ada
            'jumlah_anak' => 25,
        ]);
    }
}
