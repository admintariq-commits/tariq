<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('TARIQ SMTP Test Email')
                    ->view('emails.test-email')
                    ->with([
                        'title' => 'SMTP Configuration Test',
                        'message' => 'This is a test email to verify your SMTP settings for the TARIQ system.',
                    ]);
    }
}
