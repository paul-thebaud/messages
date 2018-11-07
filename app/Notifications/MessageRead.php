<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class MessageRead extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Message $message The message.
     */
    private $message;

    /**
     * @var User $user The user who read the message.
     */
    private $user;

    /**
     * MessageDeleted constructor.
     *
     * @param Message $message The message.
     * @param User    $user    The user who read the message.
     */
    public function __construct(Message $message, User $user)
    {
        $this->message = $message;
        $this->user    = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($notifiable)
    {
        return [
            'message_id' => $this->message->id,
            'user_id'    => $this->user->id,
        ];
    }
}
