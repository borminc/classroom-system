<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();

        return [
            'name' => 'Intro to ' . $faker->unique()->word(),
            'code' => Str::random(5),
            'description' => $faker->text($maxNbChars = 150),
            'instructor_id' => User::role('instructor')->get()->random()->id,
        ];
    }
}
