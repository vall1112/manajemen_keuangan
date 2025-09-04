<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('payment_types')->insert([
            [
                'nama_jenis' => 'SPP Januari',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP Februari',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP Maret',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP April',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP Mei',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP Juni',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP Juli',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP Agustus',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP September',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP November',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'SPP Desember',
                'keterangan' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('payment_types')
            ->whereIn('nama_jenis', [
                'SPP Januari', 'SPP Februari', 'SPP Maret', 'SPP April',
                'SPP Mei', 'SPP Juni', 'SPP Juli', 'SPP Agustus',
                'SPP September', 'SPP November', 'SPP Desember'
            ])
            ->delete();
    }
};
