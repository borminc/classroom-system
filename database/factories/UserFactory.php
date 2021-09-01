<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        $gender = $faker->randomElement(['male', 'female']);

        $firstName = $faker->unique()->firstName($gender);
        $lastName = $faker->unique()->lastName($gender);
        $username = $faker->unique()->userName();

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $username . '@test.com',
            'password' => Hash::make('12345678'),
            'gender' => $faker->randomElement(['male', 'female']),
            'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    // public function configure()
    // {
    //     return $this->afterMaking(function (User $user) {
    //         //
    //     })->afterCreating(function (User $user) {
    //         // $user->assignRole($this->faker->randomElement(['student', 'instructor']));
    //     });
    // }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
