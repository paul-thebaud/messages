<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class ConversationUser.
 *
 * @property string|null $nickname               The nickname of the user in conversation.
 * @property string      $role                   The role of the user in conversation.
 * @property string      $conversation_id        The conversation ID.
 * @property User        $conversation           The conversation instance.
 * @property string      $user_id                The writer user ID.
 * @property User        $user                   The writer user instance.
 * @property Carbon|null $created_at             The creation datetime.
 * @property Carbon|null $updated_at             The update datetime.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class ConversationUser extends Pivot
{
    /**
     * @var string ROLE_USER The normal role without special permission.
     */
    public const ROLE_USER = 'user';
    /**
     * @var string ROLE_ADMIN The admin role with all permissions.
     */
    public const ROLE_ADMIN = 'admin';

    /**
     * {@inheritdoc}
     */
    protected $attributes = [
        'role' => self::ROLE_USER,
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The conversation.
     *
     * @return BelongsTo The relation.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
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
