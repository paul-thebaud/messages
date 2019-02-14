<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\Pivots\ConversationUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ConversationPolicy.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class ConversationPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Conversation $conversation): bool
    {
        return $conversation->users()
            ->where('id', $user->id)
            ->exists();
    }

    public function update(User $user, Conversation $conversation): bool
    {
        return $this->delete($user, $conversation);
    }

    public function delete(User $user, Conversation $conversation): bool
    {
        return $conversation->users()
            ->where('id', $user->id)
            ->wherePivot('role', ConversationUser::ROLE_ADMIN)
            ->exists();
    }

    public function detach(User $user, Conversation $conversation, User $userToDetach): bool
    {
        if ($user->id === $userToDetach->id) {
            return true;
        }
        return $conversation->users()
            ->where('id', $user->id)
            ->wherePivot('role', ConversationUser::ROLE_ADMIN)
            ->exists();
    }
}
