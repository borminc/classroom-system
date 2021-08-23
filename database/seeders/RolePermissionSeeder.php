<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create roles
        $roles = [
            'admin',
            'instructor',
            'student'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }        
    }
}
