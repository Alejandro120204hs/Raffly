<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablecer contraseña – Rafflys')
            ->view('emails.reset-password', [
                'url'      => $url,
                'nombre'   => explode(' ', $notifiable->name)[0],
                'expira'   => config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60),
            ]);
    }
}
