<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->truncate();

        Setting::create([
            'uuid'            => Str::uuid(),
            'app'             => 'e-SIMAKU',
            'school'          => 'SMK AL-AZHAR MENGANTI',
            'logo'            => '/media/logo.png',
            'bg_auth'         => '/media/misc/bg-auth.jpg',
            'logo_sekolah'    => '/media/logo.png',
            'bg_landing_page' => '/media/misc/bg-auth.jpg',
            'pemerintah'      => 'Pemerintah Provinsi Jawa Timur',
            'alamat'          => '',
            'telepon'         => '',
            'email'           => 'simaku@gmail.com',
        ]);
    }
}
