<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class MessageUser.
 *
 * @property string      $message_id             The message ID.
 * @property User        $message                The message instance.
 * @property string      $user_id                The writer user ID.
 * @property User        $user                   The writer user instance.
 * @property Carbon|null $created_at             The creation datetime.
 * @property Carbon|null $updated_at             The update datetime.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class MessageUser extends Pivot
{
    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The message.
     *
     * @return BelongsTo The relation.
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * The user.
     *
     * @return BelongsTo The relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
