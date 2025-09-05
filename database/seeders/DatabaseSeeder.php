<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            ClassroomSeeder::class,
            MajorSeeder::class,
            PaymentTypeSeeder::class,
            SchoolYearSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
        ]);
    }
}
