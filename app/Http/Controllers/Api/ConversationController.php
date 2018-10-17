<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\Conversation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConversationController extends AbstractController
{
    /**
     * Fetch the conversations.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     */
    public function index(Request $request): JsonResponse
    {
        /** @todo Pagination? */
        /** @todo Search? */
        return response()->json($request->user()->conversations);
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

        return response()->json($conversation);
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

        /** @todo Allow updating with users. */
        $this->validate($request, [
            'name' => 'nullable|string|min:4|max:60',
        ]);

        $conversation->update($request->only('name'));

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
     */
    public function destroy(Conversation $conversation): JsonResponse
    {
        $this->authorize('delete', $conversation);

        $conversation->delete();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
