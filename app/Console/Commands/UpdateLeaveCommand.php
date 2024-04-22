<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateLeaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:update-leave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update leave in month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->contract == config('define.contract.staff') && $user->status == config('define.status_user.active')) {
                $user->leave_hours_left += config('define.leave_hours.leave_hours_month');
                $user->save();
                if ($user->gender == config('define.gender.female')) {
                    $user->leave_hours_left_in_month = config('define.leave_hours.leave_hours_half_month');
                    $user->save();
                }
            }
        }
    }
}
