<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class MessagePolicy.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class MessagePolicy
{
    use HandlesAuthorization;

    public function create(User $user, Conversation $conversation): bool
    {
        return $conversation->users()
            ->where('id', $user->id)
            ->exists();
    }

    public function show(User $user, Message $message): bool
    {
        return $message->conversation->users()
            ->where('id', $user->id)
            ->exists();
    }

    public function update(User $user, Message $message): bool
    {
        return $this->delete($user, $message);
    }

    public function delete(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }
}
