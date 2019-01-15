<?php

namespace App\Providers;

use App\Events\NewMessage;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $listen = [
        NewMessage::class => []
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();
    }
}
