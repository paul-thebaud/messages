<?php

namespace App\Models;

use App\Models\Concerns\UuidModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PasswordReset.
 *
 * @property string      $token     The hashed version of password.
 * @property string      $user_id   The user ID.
 * @property User        $user      The user instance.
 * @property Carbon|null $expire_at The expiration delay of this reset request.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class PasswordReset extends UuidModel
{
    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'expire_at',
    ];

    /**
     * {@inheritdoc}
     *
     * Generate token before saving.
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function (PasswordReset $passwordReset) {
            $passwordReset->token     = str_random(100);
            $passwordReset->expire_at = now()->addMinutes(15);
        });
    }

    /**
     * The user who request this reset.
     *
     * @return BelongsTo The relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}