<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $getUserIds;
    public $data;
    public $status;

    public function __construct($status, $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public function build()
    {

        $status = ucfirst($this->status);
        if ($status == 'Leave') {
            $type = $this->data->type;
            $subject = trans('mail.mail.mail_remote_register_leave');
            $style = 'Leave';
        } elseif ($status == 'Remote') {
            $subject = trans('mail.mail.mail_remote_register_subject');
            $style = 'Remote';
        }
        $getName = ucfirst(Auth::user()->code);
        $fromDate = Carbon::parse($this->data->from_datetime)->format(config('define.datetime'));
        $toDate = Carbon::parse($this->data->to_datetime)->format(config('define.datetime'));
        $getApprover = User::find($this->data->approver_id);
        $approver = $getApprover->code;
        $reason = $this->data->reason;


        return $this->view('mail.send')
            ->subject($getName . $subject . ' từ ' . $fromDate . ' đến ' . $toDate)
            ->with([
                'style' => $style,
                'type' => $type ?? "0",
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'approver' => $approver,
                'reason' => $reason,
            ]);
    }
}
