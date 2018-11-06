<?php

namespace App\Notifications;

use App\Models\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var PasswordReset $passwordReset The password reset request.
     */
    private $passwordReset;

    /**
     * ForgotPassword constructor.
     *
     * @param PasswordReset $passwordReset The password reset request.
     */
    public function __construct(PasswordReset $passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    /**
     * {@inheritdoc}
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * {@inheritdoc}
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Password reset request on **Messages** application')
            ->line('To reset your password, just click on the link below and fill the form with your new password.')
            ->action('Reset my password', url()->to('/password/reset', ['token' => $this->passwordReset->token]))
            ->line('If you do not request this password reset, please ignore this mail.')
            ->line('Thank you for using our application!');
    }
}
