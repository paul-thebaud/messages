<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\ConversationUser;
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
}
