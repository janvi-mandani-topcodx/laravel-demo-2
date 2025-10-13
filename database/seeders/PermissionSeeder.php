<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'create_user', 'guard_name' => 'web'],
            ['name' => 'update_user', 'guard_name' => 'web'],
            ['name' => 'create_role', 'guard_name' => 'web'],
            ['name' => 'update_role', 'guard_name' => 'web'],
        ];
        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }
    }
}
