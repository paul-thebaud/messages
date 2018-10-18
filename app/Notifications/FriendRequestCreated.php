<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class FriendRequestCreated extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var User $requester The user who request the other user as friend.
     */
    private $requester;

    /**
     * @var User $recipient The user who receive this request.
     */
    private $recipient;

    /**
     * FriendRequestCreated constructor.
     *
     * @param User $requester The user who request the other user as friend.
     * @param User $recipient The user who receive this request.
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
