<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Laravel\Passport\Token;

/**
 * Class UserPolicy.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class TokenPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Token $token): bool
    {
        return $user->id === $token->user_id;
    }
}
