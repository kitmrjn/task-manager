<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class WelcomeSetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Use the route() helper directly to guarantee a perfectly formatted absolute URL
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Welcome to Task Manager - Set Your Password')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('An administrator has created an account for you on the internal Task Manager system.')
            ->line('Please click the button below to set up your password and activate your account.')
            ->action('Set Password & Activate Account', $url)
            ->line('This password setup link will expire in 60 minutes.')
            ->line('If you were not expecting this invitation, you can safely ignore this email.');
    }
}