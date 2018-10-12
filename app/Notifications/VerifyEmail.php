<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var User $user The user to verify email.
     */
    private $user;

    /**
     * VerifyEmail constructor.
     *
     * @var User $user The user to verify email.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            ->line('Welcome on **Messages** application.')
            ->line(sprintf('Your username is *%s*.', $this->user->username))
            ->line('Just a last thing before using **Messages**, click this button to verify your email address.')
            ->action('Verify my email', URL::signedRoute('verify', ['user' => $this->user->id]))
            ->line('If you do not request this registration, please ignore this mail.')
            ->line('Thank you for using our application!');
    }
}