<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        Classroom::create([
            'nama_kelas' => 'XII RPL',
            'major_id' => 5,
            'wali_kelas_id' => 36,
            'jumlah_anak' => '24',
        ]);

        Classroom::create([
            'nama_kelas' => 'XII TKJ A',
            'major_id' => 2,
            'wali_kelas_id' => 38,
            'jumlah_anak' => '28',
        ]);

        Classroom::create([
            'nama_kelas' => 'XII TKJ B',
            'major_id' => 2,
            'wali_kelas_id' => 24,
            'jumlah_anak' => '26',
        ]);

        Classroom::create([
            'nama_kelas' => 'XII TPM',
            'major_id' => 4,
            'wali_kelas_id' => 20,
            'jumlah_anak' => '18',
        ]);

        Classroom::create([
            'nama_kelas' => 'XII TITL',
            'major_id' => 1,
            'wali_kelas_id' => 18,
            'jumlah_anak' => '32',
        ]);

        Classroom::create([
            'nama_kelas' => 'XII TKR',
            'major_id' => 3,
            'wali_kelas_id' => 37,
            'jumlah_anak' => '42',
        ]);

        Classroom::create([
            'nama_kelas' => 'XI TITL',
            'major_id' => 1,
            'wali_kelas_id' => 33,
            'jumlah_anak' => '29',
        ]);

        Classroom::create([
            'nama_kelas' => 'XI TKJ',
            'major_id' => 2,
            'wali_kelas_id' => 30,
            'jumlah_anak' => '33',
        ]);

        Classroom::create([
            'nama_kelas' => 'XI TKR',
            'major_id' => 3,
            'wali_kelas_id' => 25,
            'jumlah_anak' => '17',
        ]);

        Classroom::create([
            'nama_kelas' => 'XI TPM',
            'major_id' => 4,
            'wali_kelas_id' => 47,
            'jumlah_anak' => '7',
        ]);

        Classroom::create([
            'nama_kelas' => 'XI RPL',
            'major_id' => 5,
            'wali_kelas_id' => 49,
            'jumlah_anak' => '21',
        ]);

        Classroom::create([
            'nama_kelas' => 'X TITL',
            'major_id' => 1,
            'wali_kelas_id' => 19,
            'jumlah_anak' => '20',
        ]);

        Classroom::create([
            'nama_kelas' => 'X TKJ',
            'major_id' => 2,
            'wali_kelas_id' => 38,
            'jumlah_anak' => '20',
        ]);

        Classroom::create([
            'nama_kelas' => 'X TKR',
            'major_id' => 3,
            'wali_kelas_id' => 26,
            'jumlah_anak' => '20',
        ]);

        Classroom::create([
            'nama_kelas' => 'X TPM',
            'major_id' => 4,
            'wali_kelas_id' => 42,
            'jumlah_anak' => '20',
        ]);

        Classroom::create([
            'nama_kelas' => 'X RPL',
            'major_id' => 5,
            'wali_kelas_id' => 16,
            'jumlah_anak' => '20',
        ]);
    }
}
