<?php

namespace App\Models;

use App\Models\Concerns\UuidModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Conversation.
 *
 * @property string|null          $name                   The name.
 * @property Collection|User[]    $users                  The users of this conversation.
 * @property Collection|Message[] $messages               The messages of this conversation.
 * @property Carbon|null          $created_at             The creation datetime.
 * @property Carbon|null          $updated_at             The update datetime.
 * @property Carbon|null          $deleted_at             The deletion datetime.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class Conversation extends UuidModel
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
}
