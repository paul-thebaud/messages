<?php

namespace App\Models;

use App\Models\Concerns\UuidModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Message.
 *
 * @property string            $text               The text content.
 * @property string            $conversation_id    The conversation ID.
 * @property Conversation      $conversation       The conversation instance.
 * @property string            $user_id            The writer user ID.
 * @property User              $user               The writer user instance.
 * @property Collection|User[] $users              The users who read this message.
 * @property Carbon|null       $created_at         The creation datetime.
 * @property Carbon|null       $updated_at         The update datetime.
 * @property Carbon|null       $deleted_at         The deletion datetime.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class Message extends UuidModel
{
    use SoftDeletes;

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'text',
    ];

    /**
     * The conversation where the message was sent.
     *
     * @return BelongsTo The relation.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * The user who write this message.
     *
     * @return BelongsTo The relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The users who have seen this message.
     *
     * @return BelongsToMany The relation.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->as('message_user')
            ->using(MessageUser::class);
    }
}
