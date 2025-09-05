<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolYear;

class SchoolYearSeeder extends Seeder
{
    public function run(): void
    {
        SchoolYear::create([
            'tahun_ajaran' => '2020/2021',
            'status' => 'Tidak Aktif',
        ]);

        SchoolYear::create([
            'tahun_ajaran' => '2021/2022',
            'status' => 'Tidak Aktif',
        ]);
        SchoolYear::create([
            'tahun_ajaran' => '2022/2023',
            'status' => 'Tidak Aktif',
        ]);

        SchoolYear::create([
            'tahun_ajaran' => '2023/2024',
            'status' => 'Aktif',
        ]);
        SchoolYear::create([
            'tahun_ajaran' => '2024/2025',
            'status' => 'Aktif',
        ]);

        SchoolYear::create([
            'tahun_ajaran' => '2025/2026',
            'status' => 'Aktif',
        ]);
    }
}
