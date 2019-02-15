<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.User.{id}', function (User $user) {
    return true;
});

Broadcast::channel('App.Conversation.{id}', function (User $user, string $id) {
    return $user->conversations()->where('id', $id)->exists();
});

Broadcast::channel('App.Conversation.{id}.chat', function (User $user, string $id) {
    return $user->conversations()->where('id', $id)->exists();
});
