<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Traits\HasPermission;
use App\Repositories\RemoteReponsitory;
use App\Repositories\UserRepository;
use Laracasts\Flash\Flash;
use App\Mail\ApproveEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ManagerRemoteController  extends AppBaseController
{
    use HasPermission;
    private $remoteReponsitory, $userReponsitory;
    public function __construct(RemoteReponsitory $remoteRepo, UserRepository $userRepo)
    {
        $this->remoteReponsitory = $remoteRepo;
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
            $managerRemotes = $this->remoteReponsitory->searchByConditionPO($searchParams);
        } else {
            $managerRemotes = $this->remoteReponsitory->searchByConditions($searchParams);
        }
        foreach ($managerRemotes as $remote) {
            $remote->from_datetime = Carbon::parse($remote->from_datetime);
            $remote->to_datetime = Carbon::parse($remote->to_datetime);
        }

        return view('remote.manager.index')->with('managerRemotes', $managerRemotes);
    }

    public function edit($id, Request $request)
    {
        $managerRemotes = $this->remoteReponsitory->find($id);

        return view('remote.manager.edit')->with('managerRemotes', $managerRemotes);
    }


    public function approve($id, Request $request)
    {
        $managerRemotes = $this->remoteReponsitory->find($id);
        $user = $this->userReponsitory->find($managerRemotes->user_id);
        $email = $user->email;
        $status = $request->input('status');
        $comment = $request->input('comment')  ?? '';

        if ($status === config('define.remotes.approved')) {
//            Mail::to($email)->send(new ApproveEmail('Approved', $comment));
            $managerRemotes->status = config('define.remotes.approved');
            $managerRemotes->save();
        } elseif ($status === config('define.remotes.rejected')) {
//            Mail::to($email)->send(new ApproveEmail('Reject', $comment));
            $managerRemotes->status = config('define.remotes.rejected');
            $managerRemotes->save();
        } else {
            Flash::error(trans('validation.crud.erro_user'));
            return redirect(route('manager_remote.index'));
        }

        Flash::success(trans('validation.crud.approve'));


        return redirect(route('manager_remote.index'));
    }
}
