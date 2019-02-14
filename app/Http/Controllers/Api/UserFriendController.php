<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\FriendRequestCreated;
use App\Notifications\FriendRequestDeleted;
use Hootlex\Friendships\Models\Friendship;
use Hootlex\Friendships\Status;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserFriendController extends AbstractController
{
    /**
     * Fetch the friends of a user.
     *
     * @param User $user The user.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function index(User $user): JsonResponse
    {
        $this->authorize('show', $user);

        return response()->json([
            'friends'         => $user->friends()->get(),
            'friend_requests' => $user->getFriendRequests(),
        ]);
    }

    /**
     * Create a link between a user and a user.
     *
     * @param Request $request The request.
     * @param User    $user    The user who send the request.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function store(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        /** @var User $recipient */
        $recipient = User::query()->findOrFail($request->input('user_id'));

        if ($user->is($recipient) || $user->isFriendWith($recipient) || $user->hasSentFriendRequestTo($recipient)) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->befriend($recipient);

        $recipient->notify(new FriendRequestCreated($user, $recipient));

        return response()->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Update a friend request.
     *
     * @param Request    $request    The request.
     * @param User       $user       The user who received the request.
     * @param Friendship $friendship The friendship to update.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function update(Request $request, User $user, Friendship $friendship): JsonResponse
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'status' => sprintf('required|in:%s,%s', Status::ACCEPTED, Status::DENIED)
        ]);

        if ($user->is($friendship->recipient()->first())) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $friendship->update(['status' => $request->input('status')]);
        if ($friendship->status === Status::ACCEPTED) {
            $sender = $friendship->sender()->first();
            $sender->notify(new FriendRequestAccepted($sender, $user));
        }

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Delete the request or the friendship with a user.
     *
     * @param User       $user       The user who want to remove from his friend the other user.
     * @param Friendship $friendship The friendship to update.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function destroy(User $user, Friendship $friendship): JsonResponse
    {
        $this->authorize('update', $user);

        // TODO.
        $user->notify(new FriendRequestDeleted($user, $user));

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
