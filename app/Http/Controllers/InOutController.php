<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Repositories\TimesheetRepository;
use Laracasts\Flash\Flash;
use App\Events\TimesheetUpdate;
use App\Repositories\TeamRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\Checkin;

class InOutController extends Controller
{
    protected $timesheetRepository,
        $teamRepository,
        $userRepository;

    public function __construct(
        TimesheetRepository $timesheetRepository,
        TeamRepository $teamRepository,
        UserRepository $userRepository
    ) {
        $this->timesheetRepository = $timesheetRepository;
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
    }

    public function checkIn()
    {
        $user = $this->userRepository->find(Auth::id());
        $data['user_id'] = $user->id;
        $data['record_date'] = now()->format(config('define.date_search'));
        $data['check_in'] = now()->format(config('define.time'));
        $data['in_time'] = $data['check_in'];
        $data['status'] = 2;
        $timesheet = $this->timesheetRepository->create($data);
        $mail = $user->email;
        $name = $user->name;
        $mailCc = $this->teamRepository->getTeamCc($data['user_id']);
//        Mail::to($mail)->cc($mailCc)
//            ->queue(new Checkin($timesheet, $name));
        Flash::success(trans('validation.crud.checkin'));

        return redirect()->route('timesheet.home');
    }

    public function checkOut()
    {
        $user = $this->userRepository->find(Auth::id());
        $data['user_id'] = $user->id;
        $data['record_date'] = now()->format(config('define.date_search'));
        $timesheet = $this->timesheetRepository->findByConditions($data);
        if (!$timesheet) {
            Flash::success(trans('validation.crud.checkout_error'));
            return redirect()->route('timesheet.home');
        }
        $id = $timesheet->id;
        $data['check_out'] = now()->format(config('define.time'));
        $data['out_time'] = $data['check_out'];
        $timesheet = $this->timesheetRepository->update($data, $id);
        event(new TimesheetUpdate($timesheet));
        Flash::success(trans('validation.crud.checkout'));

        return redirect()->route('timesheet.home');
    }
}
