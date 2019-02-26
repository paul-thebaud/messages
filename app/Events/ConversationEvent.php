<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ConversationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $conversation;
    public $type;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Conversation $conversation
     * @param string $type
     */
    public function __construct(User $user, Conversation $conversation, string $type)
    {
        $this->user = $user;
        $this->conversation = $conversation;
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'conversationEvent';
    }
}
