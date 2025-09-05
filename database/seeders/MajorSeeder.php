<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        Major::create([
            'kode' => 'TITL',
            'nama_jurusan' => 'Teknik Instalasi Tenaga Listrik',
            'keterangan' => 'Jurusan ini mempelajari instalasi, perawatan, dan perbaikan sistem tenaga listrik pada bangunan dan industri.',
        ]);

        Major::create([
            'kode' => 'TKJ',
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            'keterangan' => 'Jurusan ini fokus pada perancangan, pengelolaan, dan pemeliharaan jaringan komputer serta perangkat keras dan lunak komputer.',
        ]);

        Major::create([
            'kode' => 'TKR',
            'nama_jurusan' => 'Teknik Kendaraan Ringan',
            'keterangan' => 'Jurusan ini mempelajari perawatan, perbaikan, dan pemeriksaan kendaraan ringan seperti mobil dan sepeda motor.',
        ]);

        Major::create([
            'kode' => 'TPM',
            'nama_jurusan' => 'Teknik Pemesinan',
            'keterangan' => 'Jurusan ini fokus pada proses pembuatan dan perawatan mesin, termasuk pengoperasian peralatan pemesinan dan CNC.',
        ]);

        Major::create([
            'kode' => 'RPL',
            'nama_jurusan' => 'Rekayasa Perangkat Lunak',
            'keterangan' => 'Jurusan ini mempelajari analisis, desain, pengembangan, dan pemeliharaan perangkat lunak serta aplikasi komputer.',
        ]);
    }
}
