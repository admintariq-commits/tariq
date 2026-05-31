<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminLoginNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ip;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $ip = null)
    {
        $this->user = $user;
        $this->ip = $ip;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Admin Login Notification')
                    ->view('emails.admin_login_notification')
                    ->with([
                        'user' => $this->user,
                        'ip' => $this->ip,
                    ]);
    }
}
