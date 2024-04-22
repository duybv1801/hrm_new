<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Checkin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $timesheet;
    public $name;
    public function __construct($timesheet, $name)
    {
        $this->timesheet = $timesheet;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.checkin')
            ->subject($this->name . ' ' . trans('Remote check-in'))
            ->with([
                'timesheet' => $this->timesheet,
                'name' => $this->name,
            ]);
    }
}
