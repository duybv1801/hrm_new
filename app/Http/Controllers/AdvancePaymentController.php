<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\createPaymentRequest;
use App\Models\AdvancePayment;
use App\Repositories\AdvancePaymentRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class AdvancePaymentController extends Controller
{
    public function __construct(
        AdvancePaymentRepository $advancePaymentRepository,
        UserRepository $userRepository
    )
    {
        $this->advancePaymentRepository = $advancePaymentRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): \Illuminate\View\View
    {
        $time = $request->time ?? now()->format('Y-m');
        $conditions = [
            'time' => $time,
            'user_id' => Auth::id(),
        ];
        $data = $conditions;
        $data['advancePayments'] = $this->advancePaymentRepository->searchByConditions($conditions);

        return view('advance_payment.index', $data);
    }

    public function manage(Request $request): \Illuminate\View\View
    {
        $time = $request->time ?: Carbon::now()->format('Y-m');
        $conditions = [
            'time' => $time,
        ];
        $data = $conditions;
        $data['advancePayments'] = $this->advancePaymentRepository->searchByConditions($conditions);

        return view('advance_payment.manage', $data);
    }

    public function create(): \Illuminate\View\View
    {
        $user = $this->userRepository->find( Auth::id(),  'base_salary');
        $baseSalary = $user->base_salary /2;
        return view('advance_payment.create', compact('baseSalary'));
    }

    public function store(createPaymentRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data['user_id'] = Auth::id();
        $data['time'] = $request->time;
        $data['reason'] = $request->reason;
        $data['payments'] = (int) $request->payments;
        $data['money'] = (int) $request->money;
        $data['bank'] = $request->bank;
        $data['account_number'] = $request->account_number;
        $data['status'] = config('define.advance_payment.pending');

        if($this->advancePaymentRepository->create($data)) {
            Flash::success(trans('Tạo đơn thành công'));

            return redirect()->route('advance_payments.index');
        }
        Flash::error(trans('Có lỗi rồi'));

        return redirect()->route('advance_payments.index');
    }

    public function show($id): \Illuminate\View\View
    {
        $advancePayment = $this->advancePaymentRepository->find($id);

        return view('advance_payment.detail', compact('advancePayment'));
    }

    public function edit($id): \Illuminate\View\View
    {
        $advancePayment = $this->advancePaymentRepository->find($id);

        return view('advance_payment.edit', compact('advancePayment'));
    }

    public function update(Request $request, $id)
    {
        $advancePayment = $this->advancePaymentRepository->find($id);
        $advancePayment->status = $request->status;
        $advancePayment->save();
        Flash::success(trans('validation.crud.approve'));

        return redirect()->route('advance_payments.manage');
    }

    public function destroy($id)
    {
        $advancePayment = $this->advancePaymentRepository->find($id);
        $advancePayment->status = 4;
        $advancePayment->save();
        Flash::success(trans('validation.crud.cancel'));

        return redirect()->route('advance_payments.index');
    }
}
