<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'id' => 1,
            'code' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
