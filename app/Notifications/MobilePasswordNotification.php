<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage; // Import VonageMessage

class MobilePasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $verificationCode;

    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    public function toVonage($notifiable)
    {
        return (new VonageMessage)
            ->content('Your verification code is: ' . $this->verificationCode);
    }

    public function toArray($notifiable)
    {
        return [
            // Your custom data here
        ];
    }
    public function via(object $notifiable): array
    {
        return ['vonage'];
    }
}
