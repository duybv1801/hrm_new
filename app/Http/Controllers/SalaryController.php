<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\SalaryRepository;
use App\Repositories\UserRepository;
use App\Services\SalaryService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Laracasts\Flash\Flash;

class SalaryController extends Controller
{
    private $salaryService;

    public function __construct(
        UserRepository $userRepo,
        SalaryService $salaryService,
        SalaryRepository $salaryRepo
    )
    {
        $this->userRepository = $userRepo;
        $this->salaryService = $salaryService;
        $this->salaryRepository = $salaryRepo;
    }

    public function index(Request $request)
    {
        $time = $request->time ?: Carbon::now()->subMonth()->format('Y-m');
        $conditions = [
            'time' => $time,
        ];
        $userIds = $request->user_ids ?: null;
        $data['salaries'] = $this->salaryRepository->searchByConditions($conditions, $userIds);
        $data['users'] = $this->userRepository->all([], null, null, ['id', 'name']);

        return view('salary.index', $data);
    }

    public function calSalary(Request $request): \Illuminate\Http\RedirectResponse
    {
        $start = Carbon::createFromFormat('Y-m', $request->time)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $request->time)->endOfMonth();
        $userIds = $request->has('user_ids') ? $request->user_ids : null;

        $users = $this->userRepository->getUserCalSalary($userIds, $start, $end);
        $totalHours = calTotalHours($start, $end);
        if($this->salaryService->calAndStoreSalaries($users, $userIds, $totalHours, $end)) {
            Flash::success(trans('Tính lương thành công'));
            return redirect()->route('salaries.index');
        }
        Flash::error(trans('Có lỗi rồi'));
        return redirect()->route('salaries.index');
    }
}
