<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class MessageDeleted extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Message $message The message.
     */
    private $message;

    /**
     * MessageDeleted constructor.
     *
     * @param Message $message The message.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function via(Notifiable $notifiable)
    {
        return ['broadcast', 'database'];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(Notifiable $notifiable)
    {
        return [
            'message_id' => $this->message->id,
        ];
    }
}
