<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\FriendRequestCreated;
use App\Notifications\FriendRequestDeleted;
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
     * @param User $requester The user who send the request.
     * @param User $recipient The user who received the request.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function create(User $requester, User $recipient): JsonResponse
    {
        $this->authorize('update', $requester);

        if ($requester->isFriendWith($recipient) || $requester->hasSentFriendRequestTo($recipient)) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $requester->befriend($recipient);

        $recipient->notify(new FriendRequestCreated($requester, $recipient));

        return response()->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Update a friend request.
     *
     * @param Request $request   The request.
     * @param User    $recipient The user who received the request.
     * @param User    $requester The user who send the request.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function update(Request $request, User $recipient, User $requester): JsonResponse
    {
        $this->authorize('update', $recipient);

        $this->validate($request, [
            'accept' => 'required|boolean'
        ]);

        if ($recipient->hasFriendRequestFrom($requester)) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->input('accept')) {
            $recipient->acceptFriendRequest($requester);
            $requester->notify(new FriendRequestAccepted($requester, $recipient));
        } else {
            $recipient->denyFriendRequest($requester);
        }

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Delete the request or the friendship with a user.
     *
     * @param User $requester The user who want to remove from his friend the other user.
     * @param User $recipient The user to remove from friends.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function destroy(User $requester, User $recipient): JsonResponse
    {
        $this->authorize('update', $requester);

        if (!$requester->hasSentFriendRequestTo($recipient) && !$requester->isFriendWith($recipient)) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $requester->unfriend($recipient);
        $recipient->notify(new FriendRequestDeleted($requester, $recipient));

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
