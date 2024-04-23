<?php

use App\Models\Setting;
use App\Models\Holiday;
use Carbon\Carbon;

if (!function_exists('calculator_working_hours')) {
    /**
     * function calculator working hours
     */
    function calculator_working_hours($check_in, $check_out)
    {
        $in = Carbon::parse($check_in);
        $out = Carbon::parse($check_out);
        if ($in->gte($out)) {
            return 0;
        }

        $setting = Setting::all()->pluck('value', 'key')->toArray();
        $startWorkingDay = Carbon::parse($setting['check_in_time']);
        $endWorkingDay = Carbon::parse($setting['check_out_time']);
        $lunStart = Carbon::parse($setting['lunch_time_start']);
        $lunchEnd = Carbon::parse($setting['lunch_time_end']);
        //cal time morning
        $checkIn = max($in, $startWorkingDay);

        $endMorningWorking = max($checkIn, min($out, $lunStart));
        $minuteMorning = $checkIn->diffInMinutes($endMorningWorking);

        $startAfternoon = max($in, $lunchEnd);
        $endAfternoon = max($startAfternoon, min($out, $endWorkingDay));
        $minuteAfternoon = $startAfternoon->diffInMinutes($endAfternoon);

        $totalMinute =  $minuteMorning + $minuteAfternoon;
        $maxTimeWorkingDay = (int) $setting['max_working_minutes_everyday_day'] * config('define.hour');
        $recordTime = min($totalMinute, $maxTimeWorkingDay);
        $block = max(1, (int) $setting['block']);

        $minutes = floor($recordTime / $block) * $block;
        return $minutes / config('define.hour');
    }

    function calTotalHours($start, $end)
    {
        $day = 0;
        while ($start->lte($end)) {
            $startCheck = $start->format(config('define.date_show'));
            if (!$start->isWeekend() && !isHoliday($startCheck)) {
                $day++;
            }
            $start->addDay();
        }
        $workingTime = Setting::where('key', 'working_time')->value('value');
        $hourPerDay = (int) $workingTime;

        return $day * $hourPerDay;
    }

    function isHoliday($date)
    {
        $holidayDates = Holiday::pluck('date')->toArray();
        $dateFormatted = Carbon::createFromFormat(config('define.date_show'), $date)->format(config('define.date_show'));
        $holidayDates = array_map(
            function ($holidayDate) {
                return Carbon::parse($holidayDate)->format(config('define.date_show'));
            },
            $holidayDates
        );

        return in_array($dateFormatted, $holidayDates);
    }

}
