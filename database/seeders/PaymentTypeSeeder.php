<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        PaymentType::create([
            'nama_jenis' => 'SPP',
            'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
        ]);

        PaymentType::create([
            'nama_jenis' => 'Uang Gedung',
            'keterangan' => 'Pembayaran uang gedung sekolah',
        ]);

        PaymentType::create([
            'nama_jenis' => 'Seragam',
            'keterangan' => 'Pembayaran seragam siswa baru',
        ]);

        PaymentType::create([
            'nama_jenis' => 'Buku Paket',
            'keterangan' => 'Pembayaran buku pelajaran',
        ]);
    }
}
