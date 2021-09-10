<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seed roles
        $roles = [
            'admin',
            'instructor',
            'student',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // seed admin's permissions
        $admin_permissions = [
            'create users',
            'view users',
            'view instructors',
            'view students',
            'edit users',
            'delete users',

            'register students-courses',
            'create courses',
            'edit courses',
            'delete courses',

            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            'view permissions',
            'edit permissions',
            'assign permissions',
            'revoke permissions',
        ];
        $admin_role = Role::where('name', 'admin')->first();
        foreach ($admin_permissions as $p) {
            $permission = Permission::create(['name' => $p, 'group' => 'Admin permission']);
            $admin_role->givePermissionTo($permission);
        }

        // seed students' permissions
        $student_permissions = [
            'take courses',
            'self-register courses',
            'view own-student-courses',
        ];
        $student_role = Role::where('name', 'student')->first();
        foreach ($student_permissions as $p) {
            $permission = Permission::create(['name' => $p, 'group' => 'Student permission']);
            $student_role->givePermissionTo($permission);
        }

        // seed instructors' permissions
        $instructor_permissions = [
            'teach courses',
            'view own-instructor-courses',
        ];
        $instructor_role = Role::where('name', 'instructor')->first();
        foreach ($instructor_permissions as $p) {
            $permission = Permission::create(['name' => $p, 'group' => 'Instructor permission']);
            $instructor_role->givePermissionTo($permission);
        }
    }
}
