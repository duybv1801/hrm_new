<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimesheetSeeder extends Seeder
{
    private $startTime;
    private $endTime;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = $this->command->ask(
            'Ngày bắt đầu(Y-m-d):',
            Carbon::parse('-30 days')->format('Y-m-d')
        );
       $end = $this->command->ask(
            'Ngày kết thúc(Y-m-d):',
            Carbon::parse('now')->format('Y-m-d')
        );
        $userId = $this->command->ask(
            'Nhập id của user nếu không sẽ tạo tất cả user.',
            ''
        );
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        if($startDate->gt($endDate)) {
            return $this->command->comment('Dữ liệu đầu vào sai!');
        }

        if(empty($userId)){
            $users = User::where('id', '<>', 0)->get()->pluck('id')->toArray();
        } else {
            $users = $userId;
        }

        $this->startTime = Carbon::parse('13:00')->timestamp;
        $this->endTime = Carbon::parse('15:00')->timestamp;
        while($startDate->lte($endDate)) {
            if(!$startDate->isWeekend()) {
                $recordDate = $startDate->format('Y-m-d');
                if(is_array($users)) {
                    foreach($users as $userId) {
                        $this->addRecord($recordDate, $userId);
                    }
                } else {
                    $this->addRecord($recordDate, $userId);
                }
            }

            $startDate->addDay();
        }
    }

    private function addRecord($recordDate, $userId) {
        if(Timesheet::where('record_date', $recordDate)
        ->where('user_id', $userId)->count() == 0) {
            $time = Carbon::parse(rand($this->startTime, $this->endTime));
            $status = 1;
            if(rand(0,9)==5){
                $time = Carbon::parse($this->endTime)->addMinutes(rand(1, 40));
                $status = 2;
            }
            $in = $time->toTimeString();
            $time->addMinutes(rand(570, 600));
            $out = $time->toTimeString();
            $working_hours = 8;
            if($status ==2) {
                $working_hours = calculator_working_hours($in, $out);
            }
            Timesheet::factory()->create([
                'user_id' => $userId,
                'record_date' => $recordDate,
                'in_time' => $in,
                'out_time' => $out,
                'check_in' => $in,
                'check_out' => $out,
                'status' => $status,
                'working_hours' => $working_hours
            ]);
        }
    }
}
