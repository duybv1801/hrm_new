<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OvertimeRepository;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateOTRequest;
use App\Repositories\HolidayRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TeamRepository;
use App\Repositories\UserRepository;
use App\Repositories\TimesheetRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Mail\ApproveOT;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SearchRequest;

class OvertimeController extends Controller
{
    private $otRepository;
    private $holidayRepository;
    private $settingRepository;
    private $teamRepository;
    private $userRepository;
    private $timesheetRepository;

    public function __construct(
        OvertimeRepository $otRepo,
        HolidayRepository $holidayRepo,
        SettingRepository $settingRepo,
        TeamRepository $teamRepo,
        UserRepository $userRepo,
        TimesheetRepository $timesheetRepo
    ) {
        $this->otRepository = $otRepo;
        $this->holidayRepository = $holidayRepo;
        $this->settingRepository = $settingRepo;
        $this->teamRepository = $teamRepo;
        $this->userRepository = $userRepo;
        $this->timesheetRepository = $timesheetRepo;
    }

    public function index(SearchRequest $request)
    {
        $searchParams = [
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
            'sort' => $request->sort,
            'column' => $request->column,
        ];
        $allOvertimes = $this->otRepository->searchByConditions($searchParams);
        $overtimes = $this->otRepository->userQuery($allOvertimes, Auth::id());
        $overtimes->getCollection()->transform(function ($item) {
            $item->total_hours = round($item->total_hours / config('define.hour'), config('define.decimal'));
            $item->salary_hours = round($item->salary_hours / config('define.hour'), config('define.decimal'));
            $item->from_datetime = Carbon::parse($item->from_datetime);
            $item->to_datetime = Carbon::parse($item->to_datetime);
            $item->approver_id = $item->approver->code;
            return $item;
        });

        return view('overtime.index', compact('overtimes'));
    }

    public function manage(SearchRequest $request)
    {
        $order = [
            config('define.overtime.registered'),
            config('define.overtime.admin_approve'),
            config('define.overtime.confirm'),
            config('define.overtime.admin_confirm'),
            config('define.overtime.approved'),
            config('define.overtime.confirmed'),
            config('define.overtime.rejected'),
            config('define.overtime.cancel'),
        ];
        $searchParams = [
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
            'sort' => $request->sort,
            'column' => $request->column,
            'order' => $order,
        ];
        $overtimesQuery = $this->otRepository->searchByConditions($searchParams);
        if ($request->user()->hasRole('po')) {
            $overtimesQuery = $this->otRepository->poQuery($overtimesQuery, Auth::id());
        }

        $allOvertimes = $overtimesQuery->get();
        $overtimes = new Collection();
        foreach ($allOvertimes as $item) {
            $item->total_hours = round($item->total_hours / config('define.hour'), config('define.decimal'));
            $item->salary_hours = round($item->salary_hours / config('define.hour'), config('define.decimal'));
            $item->from_datetime = Carbon::parse($item->from_datetime);
            $item->to_datetime = Carbon::parse($item->to_datetime);
            $item->user_id = $item->user->code;
            if (!$request->filled('query') || strpos($item->user_id, $request->query('query')) !== false) {
                $overtimes->add($item);
            }
        }
        $perPage = config('define.paginate');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $overtimes = new LengthAwarePaginator(
            $overtimes->forPage($currentPage, $perPage),
            $overtimes->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('overtime.manage', compact('overtimes'));
    }

    public function create()
    {
        $userId = Auth::id();
        $teamInfo = $this->teamRepository->getTeamInfo($userId);
        return view('overtime.create', compact('teamInfo'));
    }

    public function store(CreateOTRequest $request)
    {
        $input['user_id'] = Auth::id();
        $input['reason'] = $request->reason;
        $input['approver_id'] = $request->approver_id;
        $input['comment'] = $request->comment;
        $input['status'] = config('define.overtime.registered');
        $avatar = $request->file('evident');
        $path = 'public/upload/' . date(config('define.date_img'));
        $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
        $imagePath = $avatar->storeAs($path, $filename);
        $imageUrl = asset(Storage::url($imagePath));
        $input['evident'] = $imageUrl;
        $holidays = $this->holidayRepository->all()->pluck('date')->transform(function ($date) {
            return $date->format(config('define.date_search'));
        })->toArray();
        $startAt = Carbon::createFromFormat(config('define.datetime'), $request->input('from_datetime'));
        $endAt = Carbon::createFromFormat(config('define.datetime'), $request->input('to_datetime'));
        $input['total_hours'] = $startAt->diffInMinutes($endAt);
        $input['from_datetime'] = $startAt->format(config('define.datetime_db'));
        $input['to_datetime'] = $endAt->format(config('define.datetime_db'));
        $dateRanges = [
            [$input['from_datetime'], $input['to_datetime']]
        ];
        $result = $this->calculateDayNightMinutes($dateRanges, $holidays);
        $coefficients = $this->settingRepository->getCoefficients();
        $input['salary_hours'] = ($coefficients['day_time_ot'] * $result['dayMinutes']
            + $coefficients['night_time_ot'] * $result['nightMinutes']
            + $coefficients['ot_day_dayoff'] * $result['dayMinutesWeekend']
            + $coefficients['ot_night_dayoff'] * $result['nightMinutesWeekend']
            + $coefficients['ot_day_holiday'] * $result['dayMinutesHolidays']
            + $coefficients['ot_night_holiday'] * $result['nightMinutesHolidays']) / config('define.percents');
        $overtime = $this->otRepository->create($input);
        $overtime->user_id = $overtime->user->code;
        $overtime->approver_id = $overtime->approver->code;
//        $email = $overtime->approver->email;
        $cc = $request->cc;
        $subject = $overtime->user->name . ' ' . trans('overtime.mail_register');
        $mail = new ApproveOT($overtime, $subject);
        if (!empty($cc)) {
            foreach ($cc as $ccEmail) {
                $mail->cc($ccEmail);
            }
        }
//        Mail::to($email)->queue($mail);
        Flash::success(trans('validation.crud.created'));

        return redirect()->route('overtimes.index');
    }

    public function details($id)
    {
        $overtime = $this->otRepository->find($id);
        $overtime->total_hours = round($overtime->total_hours / config('define.hour'), config('define.decimal'));
        $overtime->salary_hours = round($overtime->salary_hours / config('define.hour'), config('define.decimal'));
        $overtime->from_datetime = Carbon::parse($overtime->from_datetime);
        $overtime->to_datetime = Carbon::parse($overtime->to_datetime);
        $overtime->user_id = $overtime->user->code;
        $overtime->approver_id = $overtime->approver->code;
        return view('overtime.details', compact('overtime'));
    }

    public function approve($id)
    {
        $overtime = $this->otRepository->find($id);
        $otApproveSettings = $this->settingRepository->otApproveSetting();
        $createTime = $overtime->created_at;
        $startTime = $overtime->from_datetime;
        $duration = $createTime->diffInHours($startTime);
        $overtime->from_datetime = Carbon::parse($overtime->from_datetime);
        if ($overtime->from_datetime->month < Carbon::now()->month) {
            Flash::success(trans('validation.crud.overtime'));
            return redirect()->route('overtimes.manage');
        };

        $overtime->to_datetime = Carbon::parse($overtime->to_datetime);
        $check = Auth::user()->hasRole('po') &&
            $otApproveSettings['ot_approve'] == config('define.yes') &&
            ($overtime->total_hours >= $otApproveSettings['total_ot_time'] * config('define.hour') ||
                $duration < $otApproveSettings['ot_registration_time']);
        return view('overtime.approve', compact('overtime', 'check'));
    }

    public function approveAction(Request $request, $id)
    {
        $overtime = $this->otRepository->find($id);
        $email = $overtime->user->email;
        $approver_id = $overtime->user->id;
        $input = [
            'approver_id' => $approver_id,
            'status' => $request->status,
            'comment' => $request->comment
        ];
        $subject = Auth::user()->name . ' ' . trans('overtime.mail_approve');
        if ($request->check && $request->status == config('define.overtime.approved')) {
            if ($overtime->status == config('define.overtime.registered')) {
                $input['status'] = config('define.overtime.admin_approve');
                $subject = trans('overtime.mail_po_approve');
                Flash::success(trans('validation.crud.approve'));
            } elseif ($overtime->status == config('define.overtime.confirm')) {
                $input['status'] = config('define.overtime.admin_confirm');
                $subject = trans('overtime.mail_po_confirm');
                Flash::success(trans('validation.crud.confirm'));
            }
        }
        if (
            $input['status'] == config('define.overtime.approved') &&
            ($overtime->status == config('define.overtime.confirm')
                || $overtime->status == config('define.overtime.admin_confirm'))
        ) {
            $input['status'] = config('define.overtime.confirmed');
//            $subject = trans('overtime.mail_confirm');
            $this->timesheetRepository->updateOT($overtime);
        }
        $overtime = $this->otRepository->update($input, $id);

        $overtime->user_id = $overtime->user->code;
        $overtime->approver_id = $overtime->approver->code;
//        Mail::to($email)->queue(new ApproveOT($overtime, $subject));

        return redirect()->route('overtimes.manage');
    }

    public function edit($id)
    {
        $overtime = $this->otRepository->find($id);
        $overtime->from_datetime = Carbon::parse($overtime->from_datetime);
        $overtime->to_datetime = Carbon::parse($overtime->to_datetime);
        $userId = Auth::id();
        $teamInfo = $this->teamRepository->getTeamInfo($userId);
        return view('overtime.edit', compact('overtime', 'teamInfo'));
    }

    public function update(Request $request, $id)
    {
        $overtime = $this->otRepository->find($id);
        $input['reason'] = $request->reason;
        $input['approver_id'] = $request->approver_id;
        $input['comment'] = $request->comment;
        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date(config('define.date_img'));
            $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $input['evident'] = $imageUrl;
            $oldPath = str_replace('/storage', 'public', $overtime->evident);
            Storage::delete($oldPath);
        }

        $holidays = $this->holidayRepository->all()->pluck('date')->transform(function ($date) {
            return $date->format(config('define.date_search'));
        })->toArray();

        $startAt = Carbon::createFromFormat(config('define.datetime'), $request->input('from_datetime'));
        $endAt = Carbon::createFromFormat(config('define.datetime'), $request->input('to_datetime'));
        $input['total_hours'] = $startAt->diffInMinutes($endAt);
        $input['from_datetime'] = $startAt->format(config('define.datetime_db'));
        $input['to_datetime'] = $endAt->format(config('define.datetime_db'));

        $dateRanges = [
            [$input['from_datetime'], $input['to_datetime']]
        ];
        $result = $this->calculateDayNightMinutes($dateRanges, $holidays);
        $coefficients = $this->settingRepository->getCoefficients();

        $input['salary_hours'] = ($coefficients['day_time_ot'] * $result['dayMinutes']
            + $coefficients['night_time_ot'] * $result['nightMinutes']
            + $coefficients['ot_day_dayoff'] * $result['dayMinutesWeekend']
            + $coefficients['ot_night_dayoff'] * $result['nightMinutesWeekend']
            + $coefficients['ot_day_holiday'] * $result['dayMinutesHolidays']
            + $coefficients['ot_night_holiday'] * $result['nightMinutesHolidays']) / config('define.percents');
        if ($overtime->status == config('define.overtime.approved')) {
            $input['status'] = config('define.overtime.confirm');
        }
        $overtime = $this->otRepository->update($input, $id);
        $overtime->user_id = $overtime->user->code;
        $overtime->approver_id = $overtime->approver->code;
//        $email = $overtime->approver->email;
        $cc = $request->cc;
        $subject = Auth::user()->name . ' ' . trans('overtime.mail_update');
        $mail = new ApproveOT($overtime, $subject);
        if (!empty($cc)) {
            foreach ($cc as $ccEmail) {
                $mail->cc($ccEmail);
            }
        }
//        Mail::to($email)->queue($mail);
        Flash::success(trans('validation.crud.updated'));

        return redirect()->route('overtimes.index');
    }

    public function cancel($id, Request $request)
    {
        $overtime = $this->otRepository->find($id);
        $overtime->status = config('define.overtime.cancel');
        $overtime->reason = $request->reason;
        $overtime->save();
        $overtime->user_id = $overtime->user->code;
        $overtime->approver_id = $overtime->approver->code;
//        $email = $overtime->approver->email;
//        $subject = Auth::user()->name . ' ' . trans('overtime.mail_cancel');
//        Mail::to($email)->queue(new ApproveOT($overtime, $subject));
        Flash::success(trans('validation.crud.cancel'));
        return redirect(route('overtimes.index'));
    }

    private function calculateDayNightMinutes($dateRanges, $holidays)
    {
        $dayMinutes = 0;
        $nightMinutes = 0;
        $dayMinutesWeekend = 0;
        $nightMinutesWeekend = 0;
        $dayMinutesHolidays = 0;
        $nightMinutesHolidays = 0;
        $nightTimeStart = $this->settingRepository->findByKey('ot_night_time_start')->value;
        list($nightStartHour, $nightStartMinutes) = explode(':', $nightTimeStart);
        $nightTimeEnd = $this->settingRepository->findByKey('ot_night_time_end')->value;
        list($nightEndHour, $nightEndMinutes) = explode(':', $nightTimeEnd);

        foreach ($dateRanges as $dateRange) {
            [$startDateTime, $endDateTime] = $dateRange;

            $start = Carbon::parse($startDateTime);
            $end = Carbon::parse($endDateTime);

            while ($start < $end) {
                $hour = $start->hour;

                if (($hour > $nightStartHour || ($hour == $nightStartHour && $start->minute >= $nightStartMinutes)) ||
                    ($hour < $nightEndHour || ($hour == $nightEndHour && $start->minute < $nightEndMinutes))
                ) {
                    if (in_array($start->format(config('define.date_search')), $holidays)) {
                        $nightMinutesHolidays++;
                    } elseif ($start->isWeekend()) {
                        $nightMinutesWeekend++;
                    } else {
                        $nightMinutes++;
                    }
                } else {
                    if (in_array($start->format(config('define.date_search')), $holidays)) {
                        $dayMinutesHolidays++;
                    } elseif ($start->isWeekend()) {
                        $dayMinutesWeekend++;
                    } else {
                        $dayMinutes++;
                    }
                }

                $start->addMinute();
            }
        }

        return [
            'dayMinutes' => $dayMinutes,
            'nightMinutes' => $nightMinutes,
            'dayMinutesWeekend' => $dayMinutesWeekend,
            'nightMinutesWeekend' => $nightMinutesWeekend,
            'dayMinutesHolidays' => $dayMinutesHolidays,
            'nightMinutesHolidays' => $nightMinutesHolidays,
        ];
    }
}
