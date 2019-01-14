<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\Conversation;
use App\Models\Pivots\ConversationUser;
use App\Notifications\ConversationDeleted;
use App\Notifications\ConversationUpdated;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class ConversationController extends AbstractController
{
    /**
     * Fetch the conversations.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     */
    public function index(Request $request): JsonResponse
    {
        $this->validate($request, [
            'search' => 'sometimes|string',
        ]);
        /** @todo Pagination? */
        return response()->json(
            $request->user()
                ->conversations()
                ->when($request->input('search'), function (Builder $query) use ($request) {
                    $query->whereNotNull('name')
                        ->where('name', 'like', sprintf('%%%s%%', $request->input('search')));
                })
                ->orderByDesc('updated_at')
                ->get()
        );
    }

    /**
     * Fetch the conversation.
     *
     * @param Conversation $conversation The conversation to show.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function show(Conversation $conversation): JsonResponse
    {
        $this->authorize('show', $conversation);

        return response()->json($conversation->load('users'));
    }

    /**
     * Create a conversation.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'type' => 'required|string|in:' . implode(',', Conversation::TYPES),
            'name' => 'nullable|string|min:4|max:60',
        ]);

        /** @var Conversation $conversation */
        $conversation = Conversation::query()->create($request->only('type', 'name'));
        $conversation->users()->attach($request->user(), ['role' => ConversationUser::ROLE_ADMIN]);

        return response()->json($conversation, JsonResponse::HTTP_CREATED);
    }

    /**
     * Update the conversation.
     *
     * @param Request      $request      The request.
     * @param Conversation $conversation The conversation to update.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws ValidationException If the request is invalid.
     */
    public function update(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('update', $conversation);

        $this->validate($request, [
            'name' => 'nullable|string|min:4|max:60',
        ]);

        $conversation->update($request->only('name'));

        Notification::send($conversation->users, new ConversationUpdated($conversation));

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Delete the conversation.
     *
     * @param Conversation $conversation The conversation to delete.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws Exception If the model does not exists.
     */
    public function destroy(Conversation $conversation): JsonResponse
    {
        $this->authorize('delete', $conversation);

        $conversation->delete();

        Notification::send($conversation->users, new ConversationDeleted($conversation));

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
