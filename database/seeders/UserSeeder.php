<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed admin
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
            'first_name' => 'admin',
            'last_name' => 'admin',
            'gender' => 'male',
            'date_of_birth' => Carbon::createFromDate(2000, 2, 3),
        ]); 
        $admin->assignRole('admin');

        // Seed instructors
        foreach (range(1, 10) as $i) {
            $instructor = User::create([
                'username' => 'i_' . $i,
                'email' =>  'i_' . $i . '@test.com',
                'password' => Hash::make('12345678'),
                'first_name' => 'Instructor',
                'last_name' => $i,
                'gender' => 'male',
                'date_of_birth' => Carbon::createFromDate(2000, 2, 3),
            ]);
            $instructor->assignRole('instructor');
        }

        // Seed students
        foreach (range(1, 10) as $i) {
            $student = User::create([
                'username' => 's_' . $i,
                'email' => 's_' . $i . '@test.com',
                'password' => Hash::make('12345678'),
                'first_name' => 'Student',
                'last_name' => $i,
                'gender' => 'female',
                'date_of_birth' => Carbon::createFromDate(2000, 2, 3),
            ]);
            $student->assignRole('student');
        }
    }
}
