<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan relasi role_has_permissions dulu
        DB::table('role_has_permissions')->delete();

        // Reset cache spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar permissions sesuai tabel yang kamu tunjukkan
        $allPermissions = [
            'dashboard',
            'master',
            'master-user',
            'master-role',
            'website',
            'setting',
            'master-teacher',
            'master-classroom',
            'master-student',
            'master-payment',
            'master-school-year',
            'bill',
            'transaction',
        ];

        // Insert permission kalau belum ada
        foreach ($allPermissions as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'api',
            ]);
        }

        // Daftar permission untuk setiap role
        $permissionsByRole = [
            'admin' => $allPermissions, // admin dapat semua
        ];

        // Assign permissions ke role
        foreach ($permissionsByRole as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'api',
            ]);

            $permissionIds = collect($permissions)->map(function ($name) {
                return Permission::whereName($name)->first()->id;
            });

            DB::table('role_has_permissions')->insert(
                $permissionIds->map(fn($id) => [
                    'role_id' => $role->id,
                    'permission_id' => $id,
                ])->toArray()
            );
        }
    }
}
