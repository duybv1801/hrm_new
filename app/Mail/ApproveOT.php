<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveOT extends Mailable
{
    use Queueable, SerializesModels;

    public $overtime;
    public $subject;

    public function __construct($overtime, $subject)
    {
        $this->overtime = $overtime;
        $this->subject = $subject;
    }


    public function build()
    {
        return $this->view('mail.approve_ot')
            ->subject($this->subject)
            ->with([
                'overtime' => $this->overtime,
            ]);
    }
}
