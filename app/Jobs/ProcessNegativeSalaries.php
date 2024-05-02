<?php

namespace App\Jobs;

use App\Models\AdvancePayment;
use App\Models\Salary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNegativeSalaries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $time;

    public function __construct($time)
    {
        $this->time = $time;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $salaries = Salary::where('net', '<', 0)
            ->where('time', $this->time)
            ->select('net', 'user_id')
            ->get();
        foreach ($salaries as $salary) {
            $advancePayment = new AdvancePayment();
            $advancePayment->user_id = $salary->user_id;
            $advancePayment->reason = 'Dư nợ tháng trước';
            $advancePayment->payments = 2;
            $advancePayment->money = abs($salary->net);
            $advancePayment->time = now()->format('Y-m');
            $advancePayment->status = 2;
            $advancePayment->save();
        }
    }
}
