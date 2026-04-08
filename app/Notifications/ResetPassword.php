<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;


class ResetPassword extends ResetPasswordBase
{
    use Queueable;
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
        return (new MailMessage)
            ->subject('Exam Manager – Reset Your Password')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We received a request to reset the password for your Etech Online Academy account.')
            ->line('To ensure your account’s security, please use the link below to create a new password.')
            ->action('Reset My Password', $resetUrl)
            ->line('This link will expire in 60 minutes for your protection.')
            ->line('If you did not request a password reset, you can safely ignore this email.')
            ->salutation('Regards, Etech Online Academy.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
