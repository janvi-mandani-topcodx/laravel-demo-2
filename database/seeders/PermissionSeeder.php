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
            ['name' => 'delete_user', 'guard_name' => 'web'],
            ['name' => 'create_role', 'guard_name' => 'web'],
            ['name' => 'update_role', 'guard_name' => 'web'],
            ['name' => 'delete_role', 'guard_name' => 'web'],
            ['name' => 'create_permission', 'guard_name' => 'web'],
            ['name' => 'update_permission', 'guard_name' => 'web'],
            ['name' => 'delete_permission', 'guard_name' => 'web'],
            ['name' => 'view_chat', 'guard_name' => 'web'],
            ['name' => 'show_chat_user', 'guard_name' => 'web'],
            ['name' => 'update_chat', 'guard_name' => 'web'],
            ['name' => 'send_message', 'guard_name' => 'web'],
            ['name' => 'delete_chat', 'guard_name' => 'web'],
            ['name' => 'create_product', 'guard_name' => 'web'],
            ['name' => 'update_product', 'guard_name' => 'web'],
            ['name' => 'delete_product', 'guard_name' => 'web'],
            ['name' => 'show_menu', 'guard_name' => 'web'],
            ['name' => 'add_to_cart_product', 'guard_name' => 'web'],
            ['name' => 'create_order', 'guard_name' => 'web'],
            ['name' => 'update_order', 'guard_name' => 'web'],
            ['name' => 'delete_order', 'guard_name' => 'web'],
            ['name' => 'create_credit', 'guard_name' => 'web'],
            ['name' => 'show_credit_logs', 'guard_name' => 'web'],
        ];
        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }
    }
}
