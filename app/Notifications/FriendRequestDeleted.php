<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class FriendRequestDeleted extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var User $requester The user who want to remove from his friend the other user.
     */
    private $requester;

    /**
     * @var User $recipient The user to remove from friends.
     */
    private $recipient;

    /**
     * FriendRequestCreated constructor.
     *
     * @param User $requester The user who want to remove from his friend the other user.
     * @param User $recipient The user to remove from friends.
     */
    public function __construct(User $requester, User $recipient)
    {
        $this->requester = $requester;
        $this->recipient = $recipient;
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
            'requester_id' => $this->requester->id,
            'recipient_id' => $this->recipient->id,
        ];
    }
}
