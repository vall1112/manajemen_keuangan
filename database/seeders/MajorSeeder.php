<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        // buat isi jurusan
        Major::create([
            'kode' => 'TITL',
            'nama_jurusan' => 'Teknik Instalasi Tenaga Listrik',
            'keterangan' => 'Fokus pada instalasi, perawatan, dan pengelolaan sistem kelistrikan',
        ]);
        Major::create([
            'kode' => 'TKJ',
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            'keterangan' => 'Fokus pada instalasi, konfigurasi, dan pemeliharaan komputer & jaringan',
        ]);
        Major::create([
            'kode' => 'TKR',
            'nama_jurusan' => 'Teknik Kendaraan Ringan',
            'keterangan' => 'Fokus pada perbaikan, perawatan, dan manajemen kendaraan ringan',
        ]);
        Major::create([
            'kode' => 'TPM',
            'nama_jurusan' => 'Teknik Pemesinan',
            'keterangan' => 'Fokus pada pembuatan, permesinan, dan pemeliharaan alat-alat industri',
        ]);
        Major::create([
            'kode' => 'RPL',
            'nama_jurusan' => 'Rekayasa Perangkat Lunak',
            'keterangan' => 'Fokus pada pengembangan aplikasi, software, dan pemrograman',
        ]);
    }
}