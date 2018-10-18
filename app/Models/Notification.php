<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;

/**
 * Class PasswordReset.
 *
 * @property string      $type              The type of this notification.
 * @property Notifiable  $notifiable        The concerned notifiable.
 * @property array       $data              The notification's data.
 * @property Carbon|null $read_at           The read datetime.
 * @property Carbon|null $created_at        The creation datetime.
 * @property Carbon|null $updated_at        The update datetime.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class Notification extends DatabaseNotification
{
}
