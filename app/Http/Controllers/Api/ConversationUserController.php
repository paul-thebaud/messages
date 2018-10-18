<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\User;
use App\Notifications\ConversationUserCreated;
use App\Notifications\ConversationUserDeleted;
use App\Notifications\ConversationUserUpdated;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Webpatser\Uuid\Uuid;

class ConversationUserController extends AbstractController
{
    /**
     * Fetch the users of a conversation.
     *
     * @param Conversation $conversation The conversation.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function index(Conversation $conversation): JsonResponse
    {
        $this->authorize('show', $conversation);

        return response()->json($conversation->users);
    }

    /**
     * Create a link between a conversation and a user.
     *
     * @param Request      $request      The request.
     * @param Conversation $conversation The conversation.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws ValidationException If the request is invalid.
     */
    public function create(Request $request, Conversation $conversation): JsonResponse
    {
        $this->validate($request, [
            'user_id' => ['required', 'string', sprintf('regex:/%s/', Uuid::VALID_UUID_REGEX)]
        ]);
        /** @var User $user */
        $user = User::query()->findOrFail($request->input('user_id'));

        $this->authorize('update', $conversation);

        $this->validate($request, [
            'nickname' => 'nullable|string|min:4|max:60',
            'role'     => 'required|string|in:' . implode(ConversationUser::ROLES),
        ]);

        // User must not be already in conversation.
        if ($conversation->users()->where('id', $user->id)->exists()
            || $request->user()->isFriendWith($user)
        ) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Binary conversation cannot have more than 2 users.
        if (Conversation::TYPE_BINARY === $conversation->type
            && $conversation->users()->count() >= 2
        ) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $conversation->users()->attach($user, $request->only('nickname', 'role'));

        $notification = new ConversationUserCreated($conversation, $user);
        $user->notify($notification);
        Notification::send($conversation->users, $notification);

        return response()->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Update the conversation and user link.
     *
     * @param Request      $request      The request.
     * @param Conversation $conversation The conversation to update.
     * @param User         $user         The user.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws ValidationException If the request is invalid.
     */
    public function update(Request $request, Conversation $conversation, User $user): JsonResponse
    {
        $this->authorize('update', $conversation);

        $this->validate($request, [
            'nickname' => 'nullable|string|min:4|max:60',
            'role'     => 'required|string|in:' . implode(ConversationUser::ROLES),
        ]);

        if ($conversation->users()->where('id', $user->id)->doesntExist()) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $conversation->users()->updateExistingPivot($user, $request->only('nickname', 'role'));

        $notification = new ConversationUserUpdated($conversation, $user);
        $user->notify($notification);
        Notification::send($conversation->users, $notification);

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Delete the link between the conversation and a user.
     *
     * @param Conversation $conversation The conversation.
     * @param User         $user         The user.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws Exception If the model does not exists.
     */
    public function destroy(Conversation $conversation, User $user): JsonResponse
    {
        $this->authorize('detach', [$conversation, $user]);

        $conversation->delete();

        $notification = new ConversationUserDeleted($conversation, $user);
        $user->notify($notification);
        Notification::send($conversation->users, $notification);

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
