<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InOutMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject;
    public $inOutForget;

    public function __construct($subject, $inOutForget)
    {
        $this->subject = $subject;
        $this->inOutForget = $inOutForget;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.in_out_forget')
            ->subject($this->subject)
            ->with([
                'inOutForget' => $this->inOutForget,
            ]);
    }
}
