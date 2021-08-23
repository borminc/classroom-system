<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [
            [
                'name' => 'Intro to Programming',
                'code' => 'CS100'
            ],
            [
                'name' => 'Data Stuctures and Algorithms',
                'code' => 'CS201'
            ],
            [
                'name' => 'Web Development',
                'code' => 'CS260'
            ]
        ];

        $instructors = User::role('instructor')->get();
        $index = 0;

        foreach ($courses as $course) {
            Course::create([
                'name' => $course['name'],
                'code' => $course['code'],
                'description' => $course['name'] . ' is an awesome course!',
                'instructor_id' => $instructors[$index]->id
            ]);

            $index++;
            if ($index >= $instructors->count()) {
                $index = 0;
            }
        }
    }
}
