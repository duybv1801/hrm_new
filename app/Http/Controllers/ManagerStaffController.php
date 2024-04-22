<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStaffRequest;
use App\Http\Requests\CreateStaffRequest;
use App\Repositories\UserRepository;
use App\Repositories\TeamRepository;
use App\Http\Controllers\AppBaseController;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Laracasts\Flash\Flash;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ManagerStaffController extends AppBaseController
{
    private $userRepository, $teamRepository, $leaveService;
    public function __construct(UserRepository $userRepo, TeamRepository $teamRepo)
    {
        $this->userRepository = $userRepo;
        $this->teamRepository = $teamRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchParams = [
            'query' => $request->input('query'),
        ];

        $users = $this->userRepository->searchByConditions($searchParams);

        return view('manager_staff.index')->with('users', $users);
    }
    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        return view('manager_staff.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateStaffRequest $request
     *
     * @return Response
     */
    public function store(CreateStaffRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $roleId = $request->input('roleId');
        $role = $this->userRepository->getRoleById($roleId);
        $user = $this->userRepository->create($input);
        $user->roles()->sync($role);
        $expirationTime = Carbon::now()->addMinutes(config('define.add_minutes'));
        $token = app('auth.password.broker')->createToken($user);
        $urlWithExpiration = URL::temporarySignedRoute(
            'password.reset',
            $expirationTime,
            ['token' => $token, 'email' => $input['email']]
        );

//        Mail::to($input['email'])->send(new VerifyEmail($urlWithExpiration));
        Flash::success(trans('Add New Complete'));

        return redirect(route('manager_staff.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $user = $this->userRepository->find($id);
        $teams = $this->teamRepository->getTeamList();

        if (empty($user)) {
            Flash::error(trans('validation.crud.show_error'));

            return redirect(route('manager_staff.index'));
        }

        return view('manager_staff.edit', compact('user', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateStaffRequest $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.erro_user'));

            return redirect(route('manager_staff.index'));
        }
        $input =  $request->all();
        $roleId = $request->input('role_id');
        $role = $this->userRepository->getRoleById($roleId);
        $teamId = $request->input('team_id');
        $team = $this->teamRepository->findTeamById($teamId);
        $input['teamId'] = $team->id;

        $user = $this->userRepository->update($input, $id);

        $user->roles()->sync($role);

        Flash::success(trans('validation.crud.updated'));

        return redirect(route('manager_staff.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('Erros'));

            return redirect(route('manager_staff.index'));
        }

        $this->userRepository->delete($id);

        Flash::success(trans('validation.crud.delete'));

        return redirect(route('manager_staff.index'));
    }
}
