<?php

namespace App\Models;

use App\Models\Concerns\UuidModel;
use App\Models\Pivots\ConversationUser;
use Carbon\Carbon;
use Hootlex\Friendships\Traits\Friendable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Thomaswelton\LaravelGravatar\Facades\Gravatar;

/**
 * Class User.
 *
 * @property string                $username                        The username (string between 4 and 60 chars).
 * @property string                $name                            The name (alias of username).
 * @property string                $email                           The email.
 * @property string                $password                        The hashed version of password.
 * @property ConversationUser|null $conversation_user               The pivot for user.
 * @property Carbon|null           $email_verified_at               The email verification datetime.
 * @property Carbon|null           $created_at                      The creation datetime.
 * @property Carbon|null           $updated_at                      The update datetime.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class User extends UuidModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    MustVerifyEmailContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use Friendable;
    use HasApiTokens;
    use MustVerifyEmail;
    use Notifiable;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'email',
        'password',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $appends = [
        'gravatar',
    ];

    /**
     * Override for mail sending.
     *
     * @return string The name attribute.
     */
    public function getNameAttribute(): string
    {
        return $this->username;
    }

    /**
     * Add the gravatar URL.
     *
     * @return string The gravatar image URL.
     */
    public function getGravatarAttribute(): string
    {
        return Gravatar::src($this->email);
    }

    /**
     * The conversations this user belongs to.
     *
     * @return BelongsToMany The relation.
     */
    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class)
            ->withPivot(['nickname', 'role'])
            ->as('conversation_user')
            ->using(ConversationUser::class);
    }
}
