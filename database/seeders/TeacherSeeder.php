<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        Teacher::create([
            'user_id' => null,
            'nip' => '198712345678',
            'nama' => 'Sutrisno,M.Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '1977-05-12',
            'no_telepon' => '081234567890',
            'email' => 'sutrisnoganteng.com',
            'alamat' => 'Jl. Merdeka No. 10, Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => 'Guru Olahraga',
            'status' => 'Tidak Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'user_id' => null,
            'nip' => '199001112233',
            'nama' => 'Nur Izarul Khumairoh, S.E.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Aceh',
            'tanggal_lahir' => '1987-01-11',
            'no_telepon' => '089876543210',
            'email' => 'izatul@gmail.com.com',
            'alamat' => 'Jl. Kenangan No. 45, Aceh Barat',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => 'Aswaja',
            'status' => 'aktif',
            'foto' => null,
        ]);
    }
}
