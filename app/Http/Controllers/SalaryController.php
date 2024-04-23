<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
class SalaryController extends Controller
{
    public function index(Request $request)
    {
        if (@$request['month'] && @$request['year']) {
            $start = Carbon::create($request['year'], $request['month'], 1)->startOfMonth();
            $end = Carbon::create($request['year'], $request['month'], 1)->endOfMonth();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }

        $startTime = $start->format('Y-m-d');
        $endTime = $end->format('Y-m-d');
        $totalHours = calTotalHours($start, $end);

        if ($request->filled('user_ids')) {
            $users = User::with(['timesheets' => function ($query) use ($startTime, $endTime) {
                $query->whereBetween('record_date', [$startTime, $endTime])
                    ->select('user_id', 'working_hours', 'overtime_hours', 'leave_hours');
            }])
                ->whereIn('id', $request->user_ids)
                ->select('id', 'name', 'base_salary', 'allowance', 'contract', 'dependent_person')
                ->get()
                ->toArray();
        } else {
            $users = User::with(['timesheets' => function ($query) use ($startTime, $endTime) {
                $query->whereBetween('record_date', [$startTime, $endTime])
                    ->select('user_id', 'working_hours', 'overtime_hours', 'leave_hours');
            }])
                ->select('id', 'name', 'base_salary', 'allowance', 'contract', 'dependent_person')
                ->get()
                ->toArray();
        }

        $dataToInsert = [];
        foreach ($users as $user) {
            $totalWorkingHours = 0;
            $totalOTHours = 0;
            $totalLeaveHours = 0;
            foreach ($user['timesheets'] as $timesheet) {
                $totalWorkingHours += $timesheet['working_hours'];
                $totalOTHours += $timesheet['overtime_hours'];
                $totalLeaveHours += $timesheet['leave_hours'];
            }

            $totalTime = $totalWorkingHours + $totalOTHours + $totalLeaveHours;
            $insurance = $user['base_salary'] * 0.105;
            $taxDependent = $user['base_salary'] - 11000000 - $user['dependent_person'] * 4400000;
            if ($taxDependent > 0) {
                $taxRanges = [5000000, 10000000, 18000000, 32000000, 52000000, 80000000];
                $percentage = [0.05, 0.1, 0.15, 0.2, 0.25, 0.3, 0.35];
                foreach ($taxRanges as $index => $range) {
                    if ($taxDependent <= $range) {
                        $tax = $taxDependent * $percentage[$index];
                        break;
                    }
                }
            }
            if($user['base_salary'] > 2000000 && in_array($user['contract'], [1, 3])) {
                $tax = $user['base_salary'] * 0.9;
                $insurance = 0;
            }
            $gross = $user['base_salary'] * $totalTime/$totalHours;
            $dataToInsert[] = [
                'user_id' => $user['id'],
                'time' => $end->format('Y-m'),
                'required_time' => $totalHours,
                'total_time' => $totalTime,
                'gross' => $gross,
                'tax' => $tax,
                'insurance' => $insurance,
                'NET' => $gross - $tax - $insurance + $user['allowance'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Salary::insert($dataToInsert);


        return view('salary.index');
    }
}
