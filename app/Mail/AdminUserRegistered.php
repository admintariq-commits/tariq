<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminUserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $graduate;

    public function __construct($graduate)
    {
        $this->graduate = $graduate;
    }

    public function build()
    {
        return $this->subject('New Graduate Registration')
                    ->view('emails.admin_user_registered')
                    ->with(['graduate' => $this->graduate]);
    }
}
