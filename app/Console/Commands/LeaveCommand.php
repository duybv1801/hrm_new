<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;

use Illuminate\Support\Carbon;

class LeaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:create-date';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new leave date staff';

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
        $startDate = $users->pluck('official_start_date')->all();
        $startDateOfficial = $users->pluck('official_employment_date')->all();
        $leaveUpdate = $users->pluck('leave_update_at')->all();

        foreach ($users as $index => $user) {
            $carbonStartDate = Carbon::parse($startDate[$index]);
            $carbonStartDateOfficial = Carbon::parse($startDateOfficial[$index]);
            if (empty($leaveUpdate[$index])) {
                $carbonLeaveUpdate = Carbon::parse('0000-00-00');
            } else {
                $carbonLeaveUpdate = Carbon::parse($leaveUpdate[$index]);
            }

            if ($carbonStartDateOfficial->isToday() && !$carbonLeaveUpdate->isToday() && isset($startDateOfficial[$index])) {
                $totalMonths = $carbonStartDate->diffInMonths($carbonStartDateOfficial);
                $leaveHoursToAdd = $totalMonths * config('define.leave_hours.leave_hours_month');
                $user->leave_hours_left += $leaveHoursToAdd;
                $user->leave_update_at = now();
                $user->contract = config('define.contract.staff');
                $user->save();
            }
        }
    }
}
