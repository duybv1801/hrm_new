<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveRequest;
use App\Repositories\LeaveRepository;
use App\Repositories\UserRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TeamRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Mail\SendEmail;
use App\Mail\ApproveEmail;
use Illuminate\Support\Facades\Mail;

class LeaveController extends AppBaseController
{
    /** @var LeaveRepository $leaveRepository*/
    private $leaveRepository, $userReponsitory, $settingRepository;
    private $teamRepository;

    public function __construct(
        LeaveRepository $leaveRepo,
        UserRepository $userRepo,
        TeamRepository $teamRepo,
        SettingRepository $settingRepo
    ) {
        $this->leaveRepository = $leaveRepo;
        $this->userReponsitory = $userRepo;
        $this->settingRepository = $settingRepo;
        $this->teamRepository = $teamRepo;
    }

    /**
     * Display a listing of the Leave.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $searchParams = [
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate'),
            'query' => $request->input('query'),
        ];
        $leaves = $this->leaveRepository->searchByConditionsLeave($searchParams);
        foreach ($leaves as $leave) {
            $leave->from_datetime = Carbon::parse($leave->from_datetime);
            $leave->to_datetime = Carbon::parse($leave->to_datetime);
        }
        return view('leave.registration.index')
            ->with('leaves', $leaves);
    }

    /**
     * Show the form for creating a new Leave.
     *
     * @return Response
     */
    public function create()
    {
        $settings =  $this->settingRepository->getAllSettings();
        $userId = Auth::id();
        $teamInfo = $this->teamRepository->getTeamInfo($userId);
        return view('leave.registration.create', compact('teamInfo', 'settings'));
    }

    /**
     * Store a newly created Leave in storage.
     *
     * @param CreateLeaveRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveRequest $request)
    {
        $user = $this->userReponsitory->find(Auth::user()->id);
        $totalHours = round($request->total_hours /60, 2);
        $type = $request->type;
        $input = $request->all();

        $columnHoursLeft = null;
        $columnCalculatorLeave = null;

        if ($type == config('define.type.paid_leave')) {
            $columnHoursLeft = 'leave_hours_left';
            $columnCalculatorLeave = 'calculator_leave';
        } elseif ($type == config('define.type.sister_leave')) {
            $columnHoursLeft = 'leave_hours_left_in_month';
            $columnCalculatorLeave = 'calculator_leave_in_month';
        }

        if ($columnHoursLeft) {
            $total = $user->$columnHoursLeft;
            $input[$columnCalculatorLeave] = $totalHours;
            $user->$columnHoursLeft = $total - $totalHours;

            if ($user->$columnHoursLeft < 0) {
                Flash::error(trans('You have used up all your leave time, please choose another form'));
                return back();
            }
            $user->update();
        }

        $input['total_hours'] = $totalHours;
//        $input['cc'] = json_encode($request->input('cc'));
//        $ccIds = json_decode($input['cc'], true);
        $approverId = $input['approver_id'];

//        $getEmail = $this->userReponsitory->getEmailsByPosition($approverId);
//        $email = $getEmail->email;
        $avatar = $request->file('evident');

        if ($avatar) {
            $path = 'public/upload/' . date(config('define.date_img'));
            $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $input['evident'] = $imageUrl;
        }

        $leave = $this->leaveRepository->create($input);


//        if ($ccIds) {
//            $multyEmails = $this->userReponsitory->getEmailsByUserIds($ccIds);
//            $ccEmails = [];
//            foreach ($multyEmails as $ccEmail) {
//                if (filter_var($ccEmail, FILTER_VALIDATE_EMAIL)) {
//                    $ccEmails[] = $ccEmail;
//                }
//            }
//
//            Mail::to($email)
//                ->cc($ccEmails)
//                ->send(new SendEmail('Leave', $leave));
//        } else {
//            Mail::to($email)
//                ->send(new SendEmail('Leave', $leave));
//        }

        Flash::success(trans('Add New Complete'));
        return redirect(route('leaves.index'));
    }

    public function edit($id)
    {
        $leave = $this->leaveRepository->find($id);
        $users = $this->userReponsitory->getUsersByPosition(config('define.role.po'));
        $codes = $this->userReponsitory->getCodes();

        return view('leave.registration.edit', compact('leave', 'codes', 'users'));
    }


    public function update($id, CreateLeaveRequest $request)
    {
        $leave = $this->leaveRepository->find($id);
        $totalHours = $request->total_hours;
        $type = $request->type;
        $roleType =  $leave->type;
        $input =  $request->all();
        if ($type == config('define.type.paid_leave')) {
            $user = $this->userReponsitory->find(Auth::user()->id);
            if ($roleType == config('define.type.paid_leave')) {
                $firstEqual = $user->leave_hours_left;
                $lastEqual = $leave->calculator_leave;
                $total = $firstEqual + $lastEqual;
            } elseif ($roleType != config('define.type.paid_leave') && $roleType != config('define.type.sister_leave')) {
                $total = $user->leave_hours_left;
            } elseif ($roleType == config('define.type.sister_leave')) {
                $equalInMonth = $user->leave_hours_left_in_month;
                $totalInMonth = $leave->calculator_leave_in_month;
                $user->leave_hours_left_in_month =  $equalInMonth + $totalInMonth;

                $total = $user->leave_hours_left;
            }

            $input['calculator_leave'] = $totalHours;
            $user->leave_hours_left = $total - $totalHours;
            if ($user->leave_hours_left < 0) {
                Flash::error(trans('You have used up all your leave time, please choose another form'));
                return back();
            }

            $user->update();
        } elseif ($type == config('define.type.sister_leave')) {
            $user = $this->userReponsitory->find(Auth::user()->id);
            if ($roleType == config('define.type.sister_leave')) {
                $firstEqual = $user->leave_hours_left_in_month;
                $lastEqual = $leave->calculator_leave_in_month;
                $total = $firstEqual + $lastEqual;
            } elseif ($roleType != config('define.type.paid_leave') && $roleType != config('define.type.sister_leave')) {
                $total = $user->leave_hours_left_in_month;
            } elseif ($roleType == config('define.type.paid_leave')) {
                $equalLeft = $user->leave_hours_left;
                $totalLeft = $leave->calculator_leave;
                $user->leave_hours_left =  $equalLeft + $totalLeft;

                $total =   $user->leave_hours_left_in_month;
            }
            $input['calculator_leave_in_month'] = $totalHours;
            $user->leave_hours_left_in_month = $total - $totalHours;
            if ($user->leave_hours_left_in_month < 0) {
                Flash::error(trans('You have used up all your leave time, please choose another form'));
                return back();
            }

            $user->update();
        } else {
            $user = $this->userReponsitory->find(Auth::user()->id);
            if ($roleType == config('define.type.paid_leave')) {
                $firstEqual = $user->leave_hours_left;
                $lastEqual = $leave->calculator_leave;
                $total = $firstEqual + $lastEqual;
                $user->leave_hours_left = $total;
            } elseif ($roleType == config('define.type.sister_leave')) {
                $firstEqual = $user->leave_hours_left_in_month;
                $lastEqual = $leave->calculator_leave_in_month;
                $total = $firstEqual + $lastEqual;
                $user->leave_hours_left_in_month = $total;
            }
            $user->update();
        }

        $user = $this->userReponsitory->find($input['approver_id']);
        $input['total_hours'] = $totalHours;
        $input['cc'] = json_encode($request->input('cc'));
        $ccIds = json_decode($input['cc'], true);
        $email = $user->email;

        if (empty($leave)) {
            Flash::error(trans('validation.crud.erro_user'));

            return redirect(route('leave.index'));
        }
        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date(config('define.date_img'));
            $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $input['evident'] = $imageUrl;

            $oldImagePath = str_replace('/storage', 'public', $leave->evident);
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }
        }
        $leave = $this->leaveRepository->update($input, $id);

//        if ($ccIds) {
//            $multyEmails = $this->userReponsitory->getEmailsByUserIds($ccIds);
//            $ccEmails = [];
//            foreach ($multyEmails as $email) {
//                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                    $ccEmails[] = $email;
//                }
//            }
//            Mail::to($email)
//                ->cc($ccEmails)
//                ->send(new SendEmail('Leave', $leave));
//        }
//        Mail::to($email)->send(new SendEmail('Leave', $leave));
        Flash::success(trans('validation.crud.updated'));

        return redirect(route('leaves.index'));
    }

    public function cancel($id, Request $request)
    {
        $leave = $this->leaveRepository->find($id);
        $getUserIds = $this->userReponsitory->find($leave->approver_id);
        $email = $getUserIds->email;
        $leave->status = config('define.leaves.cancelled');
        $status = $leave->status;
        $input =  $request->all();
        $comment = $input['comment'];
        $input['status'] = $status;
        if ($leave->type == config('define.type.paid_leave')) {
            $user = $this->userReponsitory->find(Auth::user()->id);
            $firstEqual = $user->leave_hours_left;
            $lastEqual = $leave->total_hours;
            $total = $firstEqual + $lastEqual;
            $user->leave_hours_left = $total;
            $user->save();
        } elseif ($leave->type == config('define.type.sister_leave')) {
            $user = $this->userReponsitory->find(Auth::user()->id);
            $firstEqual = $user->leave_hours_left_in_month;
            $lastEqual = $leave->total_hours;
            $total = $firstEqual + $lastEqual;
            $user->leave_hours_left_in_month = $total;
            $user->save();
        }
//        Mail::to($email)->send(new ApproveEmail('Cancelled', $comment));
        $this->leaveRepository->update($input, $id);
        Flash::success(trans('validation.crud.cancel'));

        return redirect(route('leaves.index'));
    }
}
