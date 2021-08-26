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
        // seed roles
        $roles = [
            'admin',
            'instructor',
            'student'
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

            'register students-courses',
            'create courses',
            'edit courses',
            'delete courses',
        ];
        $admin_role = Role::where('name', 'admin')->first();
        foreach ($admin_permissions as $p) {
            $permission = Permission::create(['name'=> $p]);
            $admin_role->givePermissionTo($permission);
        }

        // seed students' permissions
        $student_permissions = [
            'take courses',
            'self-register courses',
            'view own-student-courses'
        ];
        $student_role = Role::where('name', 'student')->first();
        foreach ($student_permissions as $p) {
            $permission = Permission::create(['name' => $p]);
            $student_role->givePermissionTo($permission);
        }

        // seed instructors' permissions
        $instructor_permissions = [
            'teach courses',
            'view own-instructor-courses'
        ];
        $instructor_role = Role::where('name', 'instructor')->first();
        foreach ($instructor_permissions as $p) {
            $permission = Permission::create(['name' => $p]);
            $instructor_role->givePermissionTo($permission);
        }
    }
}
