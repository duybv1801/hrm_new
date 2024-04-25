<?php

namespace App\Http\Controllers;

use App\Repositories\AdvancePaymentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvancePaymentController extends Controller
{
    public function __construct(
        AdvancePaymentRepository $advancePaymentRepository
    )
    {
        $this->advancePaymentRepository = $advancePaymentRepository;
    }

    public function index(Request $request)
    {
        $startDate = $request->start_date ?: Carbon::now()->startOfMonth()->format(config('define.date_show'));
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format(config('define.date_show'));
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        $data = $conditions;
        $data['advancePayments'] = $this->advancePaymentRepository->searchByConditions($conditions);

        return view('advance_payment.index', $data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
