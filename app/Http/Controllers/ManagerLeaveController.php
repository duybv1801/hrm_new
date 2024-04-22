<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Traits\HasPermission;
use App\Repositories\LeaveRepository;
use App\Repositories\UserRepository;
use Laracasts\Flash\Flash;
use App\Mail\ApproveEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ManagerLeaveController extends AppBaseController
{
    use HasPermission;
    private $leaveRepository, $userReponsitory;

    public function __construct(LeaveRepository $leaveRepo, UserRepository $userRepo)
    {
        $this->leaveRepository = $leaveRepo;
        $this->userReponsitory = $userRepo;
    }

    public function index(Request $request)
    {
        $searchParams = [
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate'),
            'query' => $request->input('query'),
        ];
        if (Auth::user()->hasRole('po')) {
            $managerLeaves = $this->leaveRepository->searchByConditionPO($searchParams);
        } else {
            $managerLeaves = $this->leaveRepository->searchByConditions($searchParams);
        }
        foreach ($managerLeaves as $managerLeave) {
            $managerLeave->from_datetime = Carbon::parse($managerLeave->from_datetime);
            $managerLeave->to_datetime = Carbon::parse($managerLeave->to_datetime);
        }

        return view('leave.manager.index')->with('managerLeaves', $managerLeaves);
    }

    public function edit($id, Request $request)
    {
        $managerLeaves = $this->leaveRepository->find($id);

        return view('leave.manager.edit')->with('managerLeaves', $managerLeaves);
    }


    public function confirming($id, Request $request)
    {
        $managerLeaves = $this->leaveRepository->find($id);
        $user = $this->userReponsitory->find($managerLeaves->user_id);
        $email = $user->email;
        $status = $request->input('status');
        $comment = $request->input('comment')  ?? '';

        if ($status === config('define.leaves.approved')) {
//            Mail::to($email)->send(new ApproveEmail('Approved', $comment));
            $managerLeaves->status = config('define.leaves.confirming');
            $managerLeaves->save();
        } elseif ($status === config('define.leaves.rejected')) {
            if ($managerLeaves->type == config('define.type.paid_leave')) {
                $getName = $managerLeaves->user_id;
                $getUserId = $this->userReponsitory->find($getName);
                $firstEqual = $getUserId->leave_hours_left;
                $lastEqual = $managerLeaves->total_hours;
                $total = $firstEqual + $lastEqual;
                $getUserId->leave_hours_left = $total;
                $getUserId->save();
            } elseif ($managerLeaves->type == config('define.type.sister_leave')) {
                $getName = $managerLeaves->user_id;
                $getUserId = $this->userReponsitory->find($getName);
                $firstEqual = $getUserId->leave_hours_left_in_month;
                $lastEqual = $managerLeaves->total_hours;
                $total = $firstEqual + $lastEqual;
                $getUserId->leave_hours_left_in_month = $total;
                $getUserId->save();
            }
//            Mail::to($email)->send(new ApproveEmail('Reject', $comment));
            $managerLeaves->status = config('define.leaves.rejected');
            $managerLeaves->save();
        } else {
            Flash::error(trans('validation.crud.erro_user'));
            return redirect(route('manager_leave.index'));
        }

        Flash::success(trans('validation.crud.approve'));


        return redirect(route('manager_leave.index'));
    }
    public function approve($id, Request $request)
    {
        $managerLeaves = $this->leaveRepository->find($id);
        $user = $this->userReponsitory->find($managerLeaves->user_id);
        $email = $user->email;
        $status = $request->input('status');
        $comment = $request->input('comment')  ?? '';

        if ($status === config('define.leaves.approved')) {
//            Mail::to($email)->send(new ApproveEmail('Approved', $comment));
            $managerLeaves->status = config('define.leaves.approved');
            $managerLeaves->save();
        } elseif ($status === config('define.leaves.rejected')) {
            if ($managerLeaves->type == config('define.type.paid_leave')) {
                $getName = $managerLeaves->user_id;
                $getUserId = $this->userReponsitory->find($getName);
                $firstEqual = $getUserId->leave_hours_left;
                $lastEqual = $managerLeaves->total_hours;
                $total = $firstEqual + $lastEqual;
                $getUserId->leave_hours_left = $total;
                $getUserId->save();
            } elseif ($managerLeaves->type == config('define.type.sister_leave')) {
                $getName = $managerLeaves->user_id;
                $getUserId = $this->userReponsitory->find($getName);
                $firstEqual = $getUserId->leave_hours_left_in_month;
                $lastEqual = $managerLeaves->total_hours;
                $total = $firstEqual + $lastEqual;
                $getUserId->leave_hours_left_in_month = $total;
                $getUserId->save();
            }
//            Mail::to($email)->send(new ApproveEmail('Reject', $comment));
            $managerLeaves->status = config('define.leaves.rejected');
            $managerLeaves->save();
        } else {
            Flash::error(trans('validation.crud.erro_user'));
            return redirect(route('manager_leave.index'));
        }

        Flash::success(trans('validation.crud.approve'));


        return redirect(route('manager_leave.index'));
    }
}
