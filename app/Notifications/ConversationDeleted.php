<?php

namespace App\Notifications;

use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ConversationDeleted extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Conversation $conversation The conversation.
     */
    private $conversation;

    /**
     * ConversationDeleted constructor.
     *
     * @param Conversation $conversation The conversation.
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
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
            'id' => $this->conversation->id,
        ];
    }
}