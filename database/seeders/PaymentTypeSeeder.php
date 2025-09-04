<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        PaymentType::create([
            'kode' => 'D',
            'nama_jenis' => 'KEGIATAN 1 TAHUN 25/26 TAHAP 1',
            'keterangan' => 'Pembayaran tahap 1',
        ]);

        PaymentType::create([
            'kode' => 'E',
            'nama_jenis' => 'KEGIATAN 1 TAHUN 25/26 TAHAP 2',
            'keterangan' => 'Pembayaran tahap 2',
        ]);

        PaymentType::create([
            'kode' => 'F',
            'nama_jenis' => 'KEGIATAN 1 TAHUN 25/26 TAHAP 3',
            'keterangan' => 'Pembayaran tahap 3',
        ]);

        PaymentType::create([
            'kode' => 'G',
            'nama_jenis' => 'KEGIATAN 1 TAHUN 25/26 TAHAP 4',
            'keterangan' => 'Pembayaran tahap 4',
        ]);

        PaymentType::create([
            'kode' => 'H',
            'nama_jenis' => 'KEGIATAN 1 TAHUN 25/26 TAHAP 5',
            'keterangan' => 'Pembayaran tahap 5',
        ]);

        PaymentType::create([
            'kode' => 'E',
            'nama_jenis' => 'SPP BULAN JULI',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'F',
            'nama_jenis' => 'SPP BULAN AGUSTUS',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'G',
            'nama_jenis' => 'SPP BULAN SEPTEMBER',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'H',
            'nama_jenis' => 'SPP BULAN OKTOBER',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'I',
            'nama_jenis' => 'SPP BULAN NOVEMBER',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'J',
            'nama_jenis' => 'SPP BULAN DESEMBER',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'K',
            'nama_jenis' => 'SPP BULAN JANUARI',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'L',
            'nama_jenis' => 'SPP BULAN FEBRUARI',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'M',
            'nama_jenis' => 'SPP BULAN MARET',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'N',
            'nama_jenis' => 'SPP BULAN APRIL',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'O',
            'nama_jenis' => 'SPP BULAN MEI',
            'keterangan' => null,
        ]);

        PaymentType::create([
            'kode' => 'P',
            'nama_jenis' => 'SPP BULAN JUNI',
            'keterangan' => null,
        ]);
    }
}
