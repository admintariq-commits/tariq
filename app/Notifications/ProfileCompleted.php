<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Graduate;

class ProfileCompleted extends Notification
{
    use Queueable;

    protected $graduate;

    public function __construct(Graduate $graduate)
    {
        $this->graduate = $graduate;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $name = $this->graduate->getFullNameAttribute() ?? $notifiable->name;
        return (new MailMessage)
                    ->subject('Profile Completed — TARIQ')
                    ->greeting("Hello {$notifiable->name},")
                    ->line("The profile for {$name} has been completed on TARIQ.")
                    ->line('Thank you for keeping your profile up to date. Employers will now see you in matching results.')
                    ->action('View Profile', url('/graduate/profile'))
                    ->line('— TARIQ Team');
    }
}
