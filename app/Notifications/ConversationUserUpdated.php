<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ConversationUserUpdated extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Conversation $conversation The conversation.
     */
    private $conversation;

    /**
     * @var User $user The user who was added to this conversation.
     */
    private $user;

    /**
     * ConversationUserUpdated constructor.
     *
     * @param Conversation $conversation The conversation.
     * @param User         $user         The user who was added to this conversation.
     */
    public function __construct(Conversation $conversation, User $user)
    {
        $this->conversation = $conversation;
        $this->user         = $user;
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
        return $this->conversation
            ->users()
            ->where('id', $this->user->id)
            ->first()
            ->conversation_user
            ->toArray();
    }
}
