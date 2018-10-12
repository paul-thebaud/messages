<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class UserPolicy
{
    use HandlesAuthorization;

    public function show(User $user, User $userToShow): bool
    {
        return $user->isFriendWith($userToShow);
    }

    public function update(User $user, User $userToUpdate): bool
    {
        return $this->delete($user, $userToUpdate);
    }

    public function delete(User $user, User $userToDelete): bool
    {
        return $user->id === $userToDelete->id;
    }
}
