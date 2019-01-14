<?php

namespace App\Models;

use App\Models\Concerns\UuidModel;
use App\Models\Pivots\ConversationUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Conversation.
 *
 * @property string                $type                            The type (binary or group).
 * @property string|null           $name                            The name.
 * @property Collection|User[]     $users                           The users of this conversation.
 * @property Collection|Message[]  $messages                        The messages of this conversation.
 * @property ConversationUser|null $conversation_user               The pivot for user.
 * @property Carbon|null           $created_at                      The creation datetime.
 * @property Carbon|null           $updated_at                      The update datetime.
 * @property Carbon|null           $deleted_at                      The deletion datetime.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class Conversation extends UuidModel
{
    use SoftDeletes;

    /**
     * @var string TYPE_BINARY The conversation of 2 users maximum.
     */
    public const TYPE_BINARY = 'binary';
    /**
     * @var string TYPE_GROUP The conversation of 1..n users.
     */
    public const TYPE_GROUP = 'group';
    /**
     * @var string[] TYPES The available types of conversations.
     */
    public const TYPES = [self::TYPE_BINARY, self::TYPE_GROUP];

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
        'type',
        'name',
    ];

    /**
     * {@inheritdoc}
     */
    protected $appends = [
        'last_message',
    ];

    /**
     * The users of this conversation.
     *
     * @return BelongsToMany The relation.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['nickname', 'role'])
            ->as('conversation_user')
            ->using(ConversationUser::class);
    }

    /**
     * The messages of this conversation.
     *
     * @return HasMany The relation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the last message sent.
     *
     * @return string|null The last message content.
     */
    public function getLastMessageAttribute(): ?string
    {
        $message = $this->messages()->orderByDesc('created_at')->first();
        return $message ? $message->text : null;
    }
}
