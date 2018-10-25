<?php

namespace App\Providers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Policies\ConversationPolicy;
use App\Policies\MessagePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\TokenPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Token;

/**
 * Class AuthServiceProvider.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $policies = [
        Conversation::class => ConversationPolicy::class,
        Message::class      => MessagePolicy::class,
        Notification::class => NotificationPolicy::class,
        Token::class        => TokenPolicy::class,
        User::class         => UserPolicy::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
