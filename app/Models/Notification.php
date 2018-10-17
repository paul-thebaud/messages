<?php

namespace App\Models;

use App\Models\Concerns\UuidModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Class Notification.
 *
 * @property string      $type       The type of this notification.
 * @property Notifiable  $notifiable The notifiable link to this notification.
 * @property array       $data       The data of this notification.
 * @property Carbon|null $read_at    The read datetime.
 * @property Carbon|null $created_at The creation datetime.
 * @property Carbon|null $updated_at The update datetime.
 * @property Carbon|null $deleted_at The deletion datetime.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class Notification extends UuidModel
{
    use SoftDeletes;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'read_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'read_at',
    ];

    /**
     * The notifiable who receive this notification.
     *
     * @return MorphTo The relation.
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mark the notification as read with the current datetime.
     *
     * @return Notification This notification.
     */
    public function markNotificationAsRead(): self
    {
        $this->read_at = now();
        return $this;
    }
}
