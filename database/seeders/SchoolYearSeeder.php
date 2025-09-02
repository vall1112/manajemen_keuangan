<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolYear;

class SchoolYearSeeder extends Seeder
{
    public function run(): void
    {
        SchoolYear::create([
            'tahun_ajaran' => '2023/2024',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        SchoolYear::create([
            'tahun_ajaran' => '2023/2024',
            'semester' => 'Genap',
            'status' => 'tidak aktif',
        ]);
        SchoolYear::create([
            'tahun_ajaran' => '2024/2025',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        SchoolYear::create([
            'tahun_ajaran' => '2024/2025',
            'semester' => 'Genap',
            'status' => 'tidak aktif',
        ]);
        SchoolYear::create([
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        SchoolYear::create([
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Genap',
            'status' => 'tidak aktif',
        ]);
    }
}
