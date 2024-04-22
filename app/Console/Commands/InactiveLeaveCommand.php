<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Carbon;

class InactiveLeaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:resignation-date';


    protected $description = 'Resignation Date';

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
            $leaveHours = $user->leave_hours_left;
            $resignationDate = $user->resignation_date;
            $resignationUpdate = $user->resignation_update_at;

            $carbonResignationDate = Carbon::parse($resignationDate);
            if (empty($resignationUpdate)) {
                $carbonResignationUpdate = Carbon::parse('0000-00-00');
            } else {
                $carbonResignationUpdate = Carbon::parse($resignationUpdate);
            }

            if ($carbonResignationDate->isToday() && !$carbonResignationUpdate->isToday() && isset($resignationDate)) {
                $carbonStartDateLeft = Carbon::parse($resignationDate);
                $startOfMonth = $carbonStartDateLeft->copy()->startOfMonth();
                $totalDaysLeft = $startOfMonth->diffInDays($carbonStartDateLeft) + config('define.leave_hours.leave_days');
                if ($totalDaysLeft <= config('define.leave_hours.leave_days_month')) {
                    $totalSalaryDays = $leaveHours + config('define.leave_hours.leave_hours_half_month');
                } else {
                    $totalSalaryDays = $leaveHours + config('define.leave_hours.leave_hours_month');
                }
                $user->resignation_update_at = now();
                $user->status = config('define.status_user.inactive');
                $user->leave_hours_left = $totalSalaryDays;
                $user->save();
            }
        }
    }
}
