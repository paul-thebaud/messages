<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * Class UuidModel.
 *
 * @property string $id UUID version 4 as model primary key.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class UuidModel extends Model
{
    /**
     * {@inheritdoc}
     *
     * No auto-increment because of generated UUID string.
     */
    public $incrementing = false;

    /**
     * {@inheritdoc}
     *
     * String type because of generated UUID string.
     */
    public $keyType = 'string';

    /**
     * {@inheritdoc}
     *
     * Generate UUID identifier before saving.
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function (UuidModel $model) {
            $model->id = (string)Uuid::generate(4);
        });
    }
}
