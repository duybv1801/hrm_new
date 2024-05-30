<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Repositories\RewardRepository;
use App\Services\RewardService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;

class RewardController extends Controller
{
    private $rewardService;

    public function __construct(
        RewardRepository $rewardRepository,
        RewardService $rewardService
    ) 
    {
        $this->rewardRepository = $rewardRepository;
        $this->rewardService = $rewardService;
    }

    public function index(Request $request)
    {
        $conditions = [
            'time' => $request->time ?? now()->format('Y-m'),
        ];
        if ($request->user_id) {
            $conditions['user_id'] = $request->user_id;
        }

        $rewards = $this->rewardRepository->searchByConditions($conditions);

        return view('reward.index', compact('rewards'));
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => [
                'required',
                'file',
                'mimes:csv,txt',
            ],
        ]);

        if ($validator->fails()) {
            Session::flash('status', trans('Lỗi import file'));
            return redirect()->back()->withInput();
        }
        
        $file = $request->file('csv_file');
        Log::alert('before service');
        $this->rewardService->import($file, $request->time);

        Flash::success(trans('validation.crud.imported'));

        return redirect()->route('reward.index');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id) 
    {
        $reward = Reward::find($id);
        $reward->delete();
        Flash::error(trans('Xóa thành công'));

        return redirect()->route('reward.index');
    }
}
