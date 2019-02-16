<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\Conversation;
use App\Models\Pivots\ConversationUser;
use App\Models\User;
use App\Notifications\NewConversation;
use App\Notifications\RemoveConversation;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required|string|uuid'
        ]);
        /** @var User $user */
        $user = User::query()->findOrFail($request->input('user_id'));

        $this->authorize('update', $conversation);

        $this->validate($request, [
            'nickname' => 'nullable|string|min:4|max:60',
            'role'     => 'nullable|string|in:' . implode(ConversationUser::ROLES),
        ]);

        // User must not be already in conversation.
        if ($conversation->users()->where('id', $user->id)->exists()) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $conversation->users()->attach($user,
            [
                'role'=>ConversationUser::ROLE_ADMIN,
                'nickname'=>$request->input('nickname')
            ]
        );

        $user->notify(new NewConversation($conversation));

        return response()->json('', JsonResponse::HTTP_CREATED);
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

        $conversation->users()->detach($user->id);

        $user->notify(new RemoveConversation($conversation));

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
