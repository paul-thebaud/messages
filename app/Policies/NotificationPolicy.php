<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class NotificationPolicy.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class NotificationPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Notification $notification): bool
    {
        return $this->delete($user, $notification);
    }

    public function delete(User $user, Notification $notification): bool
    {
        return $notification->notifiable->id === $user->id;
    }
}
