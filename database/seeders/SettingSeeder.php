<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::truncate();
        Setting::upsert(
            [
                ['key' => 'start_working', 'value' => '7:00'],
                ['key' => 'end_working', 'value' => '18:30'],
                ['key' => 'lunch_time_start', 'value' => '11:30'],
                ['key' => 'lunch_time_end', 'value' => '13:00'],
                ['key' => 'working_time', 'value' => '480'],
                ['key' => 'block', 'value' => '15'],
            ],
            ['key'],
            ['value']
        );
    }
}