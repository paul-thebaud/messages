<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessage;
use App\Http\Controllers\AbstractController;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\MessageDeleted;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class MessageController extends AbstractController
{
    /**
     * Fetch the messages from a conversation.
     *
     * @param Request      $request      The request.
     * @param Conversation $conversation The conversation to use.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws ValidationException If the request is invalid.
     */
    public function index(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('show', $conversation);

        $this->validate($request, [
            'skip' => 'nullable|integer|min:0'
        ]);

        $query = $conversation->messages()
            ->skip($request->input('skip', 0))
            ->take(20);

        /** @todo Pagination. */
        return response()->json($query->get());
    }

    /**
     * Create a message in a conversation.
     *
     * @param Request      $request      The request.
     * @param Conversation $conversation The conversation to use.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws ValidationException If the request is invalid.
     */
    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('create', [Message::class, $conversation]);

        $this->validate($request, [
            'text' => 'required|string|min:1|max:50000',
        ]);

        $message = new Message($request->only('text'));
        $message->user()->associate($request->user());
        $message->conversation()->associate($conversation);
        $message->save();

        $message->makeHidden([
            'conversation',
            'user',
        ]);

        event(new NewMessage($message));

        return response()->json($message, JsonResponse::HTTP_CREATED);
    }

    /**
     * Delete the message.
     *
     * @param Message $message The message to delete.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws Exception If the model does not exists.
     */
    public function destroy(Message $message): JsonResponse
    {
        $this->authorize('delete', $message);

        $message->delete();

        Notification::send($message->conversation->users, new MessageDeleted($message));

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
