<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;

    public function __construct($verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Create a new message instance.
     *
     * @return void
     */
   

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.otp', ['email' => $this->verificationUrl])
        ->subject(trans('mail.mail.mail_auth'));
    }
}
