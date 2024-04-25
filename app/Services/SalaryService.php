<?php
declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Salary;

class SalaryService
{
    public function calAndStoreSalaries($users, $userIds = null, $totalHours, $end): bool|Salary
    {
        DB::beginTransaction();
        try {
            $time = Carbon::parse($end)->format('Y-m');
            $this->delErrorSalaries($userIds, $time);
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
                $gross = $user['base_salary'] * $totalTime/$totalHours;

                $insurance = $user['base_salary'] * 0.105;
                $taxDependent = $gross - 11000000 - $user['dependent_person'] * 4400000;
                if ($taxDependent > 0) {
                    $taxRanges = [5000000, 10000000, 18000000, 32000000, 52000000, 80000000];
                    $percentage = [0.05, 0.1, 0.15, 0.2, 0.25, 0.3, 0.35];
                    foreach ($taxRanges as $index => $range) {
                        if ($taxDependent <= $range) {
                            $tax = $taxDependent * $percentage[$index];
                            break;
                        }
                    }
                } else{
                    $tax = 0;
                }
                if($user['base_salary'] > 2000000 && in_array($user['contract'], [1, 3])) {
                    $tax = $user['base_salary'] * 0.1;
                    $insurance = 0;
                }
                $dataToInsert[] = [
                    'user_id' => $user['id'],
                    'time' => $end->format('Y-m'),
                    'required_time' => $totalHours,
                    'total_time' => $totalTime,
                    'gross' => $gross,
                    'tax' => $tax,
                    'insurance' => $insurance,
                    'net' => $gross - $tax - $insurance + $user['allowance'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if($result = Salary::insert($dataToInsert)){
                DB::commit();
                return $result;
            }
            return false;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Error "calAndStoreSalaries" : ' . $ex->getMessage() . ' - ' . $ex->getLine() . ' - '.$ex->getFile());
            return false;
        }
    }

    private function delErrorSalaries($userIds = null, $time): void
    {
        $query = Salary::where('time', $time);

        if ($userIds === null) {
            $query->delete();
        } else {
            $query->whereIn('user_id', $userIds)->delete();
        }
    }
}
