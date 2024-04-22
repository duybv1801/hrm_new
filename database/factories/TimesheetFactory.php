<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimesheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first(),
            'record_date' => date('Y-m-d'),
            'in_time' => '7:00',
            'out_time' => '18:30',
            'check_in' => '7:30',
            'check_out' => '18:30',
            'status' => 1,
            'working_hours' => 8,
            'overtime_hours' => 0
        ];
    }
}
