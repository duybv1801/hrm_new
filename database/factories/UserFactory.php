<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => null,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'code' => function ($attributes) {
                $normalized_name = Str::slug($attributes['name'], '');
                $code = substr($normalized_name, 0, 5);
                $code .= mt_rand(1, 5);
                return $code;
            },

            'start_date' => Carbon::parse('- ' . rand(60, 1000) . ' days')->format('Y-m-d'),
            'official_start_date' => function ($attributes) {
                return Carbon::parse($attributes['start_date'])->addMonths(2)->format('Y-m-d H:i:s');
            },
            'dependent_person' => 0,
            'gender' => rand(1, 2),
            'contract' => 1,
            'birthday' => Carbon::parse('-' . rand(7300, 21900) . ' days')->format('Y-m-d'),
            'leave_hours_left' => rand(1, 32),
            'leave_hours_left_in_month' => function ($attributes) {
                return $attributes['gender'] == 2 ? 4 : 0;
            },
            'phone' => '',
            'status' => 1,
            'position' => 1,
            'user_id' => null,
            'avatar' => null,
            'role_id' => 1
        ];
    }

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
