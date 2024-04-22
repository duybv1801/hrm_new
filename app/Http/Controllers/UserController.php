<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends AppBaseController
{
    /** @var $userRepository UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->all();
        $currentUser = $this->userRepository->find(auth()->id());

        return view('users.index', compact('users', 'currentUser'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('validation.crud.error_user');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('user', $user);
    }
    public function password($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('validation.crud.erro_user');

            return redirect(route('users.change_password'));
        }

        return view('users.password')->with('user', $user);
    }
    public function changePassword($id, Request $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.erro_user'));
            return redirect(route('users.index'));
        }

        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $input = $request->only(['password']);

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $this->userRepository->update($input, $id);

        Flash::success(trans('validation.crud.updated'));

        return redirect(route('users.index'));
    }
    public function update($id, Request $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.erro_user'));
            return redirect(route('users.index'));
        }

        $input = $request->only(['first_name', 'last_name']);
        $avatar = $request->file('avatar');
        if ($avatar) {
            $path = 'public/upload/' . date(config('define.date_img'));
            $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $input['avatar'] = $imageUrl;

            if ($user->avatar) {
                $oldImagePath = str_replace('/storage', 'public', $user->avatar);
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }
        }

        $user = $this->userRepository->update($input, $id);

        Flash::success(trans('validation.crud.updated'));

        return redirect(route('users.index'));
    }


    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.show_error'));

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success(trans('validation.crud.delete'));

        return redirect(route('users.index'));
    }
}
