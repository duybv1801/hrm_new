<?php

namespace App\Http\Controllers;

use App\Models\InOutForget;
use App\Repositories\TimesheetRepository;
use App\Repositories\TeamRepository;
use App\Repositories\InOutForgetRepository;
use App\Repositories\SettingRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\InOutForgetRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;
use App\Mail\InOutMail;
use App\Events\TimesheetUpdate;
use App\Models\Timesheet;

class InOutForgetController extends Controller
{
    protected $timesheetRepository;
    protected $teamRepository;
    protected $inOutForgetRepository;
    protected $settingRepository;
    protected $userRepository;

    public function __construct(
        TimesheetRepository $timesheetRepository,
        TeamRepository $teamRepository,
        InOutForgetRepository $inOutForgetRepository,
        SettingRepository $settingRepository,
        UserRepository $userRepository
    ) {
        $this->teamRepository = $teamRepository;
        $this->timesheetRepository = $timesheetRepository;
        $this->inOutForgetRepository = $inOutForgetRepository;
        $this->settingRepository = $settingRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $startDate = $request->start_date ?: Carbon::now()->startOfMonth()->format(config('define.date_show'));
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format(config('define.date_show'));
        $userId = Auth::id();
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_id' =>  $userId,
        ];
        $data = $conditions;
        $data['inOutForgetData'] = $this->inOutForgetRepository->searchByConditions($conditions);
        $data['inOutForgetData']->getCollection()->transform(function ($item) {
            $item->in_time = Carbon::parse($item->in_time)->format(config('define.time'));
            $item->out_time = Carbon::parse($item->out_time)->format(config('define.time'));
            $item->total_hours = round($item->total_hours / config('define.hour'), config('define.decimal'));
            $item->date = Carbon::parse($item->date)->format(config('define.date_show'));
            return $item;
        });
        return view('in_out_forgets.index', $data);
    }

    public function manage(Request $request)
    {
        $startDate = $request->start_date ?: Carbon::now()->startOfMonth()->format(config('define.date_show'));
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format(config('define.date_show'));
        $userId = Auth::id();
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        $data = $conditions;
        if (Auth::user()->hasRole('po')) {
            $member = $this->teamRepository->getMember($userId);
            $conditions['user_ids'] = $member['userIds'];
            $data['users'] = $member['userData'];
        } else {
            $data['users'] = $this->userRepository->all([], null, null, ['id', 'name']);
        }

        $data['inOutForgetData'] = $this->inOutForgetRepository->searchByConditions($conditions);
        $data['inOutForgetData']->getCollection()->transform(function ($item) {
            $item->in_time = Carbon::parse($item->in_time)->format(config('define.time'));
            $item->out_time = Carbon::parse($item->out_time)->format(config('define.time'));
            $item->total_hours = round($item->total_hours / config('define.hour'), config('define.decimal'));
            $item->date = Carbon::parse($item->date)->format(config('define.date_show'));
            return $item;
        });
        return view('in_out_forgets.manage', $data);
    }

    public function create(Request $request)
    {
        $date = $request->get('date', date(config('define.date_show')));
        $date = Carbon::createFromFormat(config('define.date_show'), $date)->format(config('define.date_search'));
        $timesheet = $this->timesheetRepository->findByConditions([
            'record_date' => $date,
            'user_id' => Auth::id(),
        ]);
        $data['timesheet'] = $timesheet;
        $inOutForget = new InOutForget();
        $inOutForget->date = $date;
        if ($timesheet) {
            $inOutForget->in_time = $timesheet->in_time;
            $inOutForget->out_time = $timesheet->out_time;
        }
        $data['inOutForget'] = $inOutForget;
        $userId = Auth::id();
        $data['teamInfo'] = $this->teamRepository->getTeamInfo($userId);
        return view('in_out_forgets.create', $data);
    }

    public function store(InOutForgetRequest $request)
    {
        $data['user_id'] = Auth::id();
        $date = Carbon::createFromFormat(config('define.date_show'), $request->date)->format(config('define.date_search'));
        $data['date'] = $date;
        $data['in_time'] = $request->in_time;
        $data['out_time'] = $request->out_time;
        $data['reason'] = $request->reason;
        $data['approver_id'] = $request->approver_id;
        $avatar = $request->file('evident');
        $path = 'public/upload/' . date(config('define.date_img'));
        $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
        $imagePath = $avatar->storeAs($path, $filename);
        $imageUrl = asset(Storage::url($imagePath));
        $data['evident'] = $imageUrl;
        $data['total_hours'] = $this->calTotalHours($data['in_time'], $data['out_time']);
        $data['status'] = config('define.in_out.register');
        $inOutForget = $this->inOutForgetRepository->create($data);
//        $email = $inOutForget->approver->email;
//        $subject = $inOutForget->user->name . ' ' . trans('inout.mail_register');
//        Mail::to($email)->queue(new InOutMail($subject, $inOutForget));
        Flash::success(trans('validation.crud.created'));
        return redirect()->route('in_out_forgets.index');
    }

    public function detail(InOutForget $inOutForget)
    {
        $inOutForget->date = Carbon::parse($inOutForget->date)->format(config('define.date_show'));
        $data['inOutForget'] = $inOutForget;
        return view('in_out_forgets.detail', $data);
    }

    public function approve(InOutForget $inOutForget)
    {
        $inOutForget->date = Carbon::parse($inOutForget->date)->format(config('define.date_show'));
        $data['inOutForget'] = $inOutForget;
        return view('in_out_forgets.approve', $data);
    }

    public function approveAction(InOutForget $inOutForget, Request $request)
    {
        $inOutForget->status = $request->status;
        $inOutForget->comment = $request->comment;
        $inOutForget->save();
        $timesheet = $this->timesheetRepository->findByConditions([
            'record_date' => $inOutForget->date,
            'user_id' => $inOutForget->user_id,
        ]);
        if (!$timesheet) {
            $timesheet = new Timesheet;
            $timesheet->user_id = $inOutForget->user_id;
            $timesheet->record_date = $inOutForget->date;
        }
        $timesheet->in_time = $inOutForget->in_time;
        $timesheet->out_time = $inOutForget->out_time;
        $timesheet->save();
        event(new TimesheetUpdate($timesheet));

//        $email = $inOutForget->user->email;
//        $subject = Auth::user()->name . ' ' . trans('inout.mail_approve');
//        Mail::to($email)->queue(new InOutMail($subject, $inOutForget));
        Flash::success(trans('validation.crud.approve'));

        return redirect()->route('in_out_forgets.manage');
    }

    public function edit(InOutForget $inOutForget)
    {
        $inOutForget->date = Carbon::parse($inOutForget->date)->format(config('define.date_show'));
        $data['user'] = Auth::user();
        $data['teamInfo'] = $this->teamRepository->getTeamInfo($data['user']->id);
        $data['inOutForget'] = $inOutForget;
        return view('in_out_forgets.edit', $data);
    }

    public function update(InOutForget $inOutForget, InOutForgetRequest $request)
    {
        $data['in_time'] = $request->in_time;
        $data['out_time'] = $request->out_time;
        $data['reason'] = $request->reason;
        $data['approver_id'] = $request->approver_id;
        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date(config('define.date_img'));
            $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $data['evident'] = $imageUrl;
            $oldPath = str_replace('/storage', 'public', $inOutForget->evident);
            Storage::delete($oldPath);
        }
        $inOutForget = $this->inOutForgetRepository->update($data, $inOutForget->id);
//        $email = $inOutForget->approver->email;
//        $subject = $inOutForget->user->name . ' ' . trans('inout.mail_update');
//        Mail::to($email)->queue(new InOutMail($subject, $inOutForget));
        Flash::success(trans('validation.crud.updated'));
        return redirect()->route('in_out_forgets.index');
    }

    public function cancel(InOutForget $inOutForget, Request $request)
    {
        $inOutForget->status = config('define.in_out.cancel');
        $inOutForget->reason = $request->reason;
        $inOutForget->save();
//        $email = $inOutForget->approver->email;
//        $subject = $inOutForget->user->name . ' ' . trans('inout.mail_cancel');
//        Mail::to($email)->queue(new InOutMail($subject, $inOutForget));
        Flash::success(trans('validation.crud.cancel'));
        return redirect()->route('in_out_forgets.index');
    }

    private function calTotalHours($inTime, $outTime)
    {
        $checkIn = Carbon::parse($inTime);
        $checkOut = Carbon::parse($outTime);
        $settings = $this->settingRepository->getTimeLunch();
        $breakStartTime = Carbon::createFromFormat(config('define.time'), $settings['lunch_time_start']);
        $breakEndTime = Carbon::createFromFormat(config('define.time'), $settings['lunch_time_end']);
        $totalDuration = $checkOut->diffInMinutes($checkIn);
        $overlapStart = $checkIn->max($breakStartTime);
        $overlapEnd = $checkOut->min($breakEndTime);
        $overlapDuration = $overlapEnd->diffInMinutes($overlapStart);
        $totalDuration -= $overlapDuration;
        $maxWorkingMinutes = $settings['max_working_minutes_everyday_day'] * config('define.hour');
        $totalDuration = min($totalDuration, $maxWorkingMinutes);

        return $totalDuration;
    }
}
