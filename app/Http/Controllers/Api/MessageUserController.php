<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\MessageRead;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class MessageUserController extends AbstractController
{
    /**
     * Create a link between message and user (read state for user or reaction).
     *
     * @param Request      $request      The request.
     * @param Conversation $conversation The conversation to use.
     * @param Message      $message      The message to use.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function create(Request $request, Conversation $conversation, Message $message): JsonResponse
    {
        $this->authorize('read', $message);

        abort_if($conversation->id !== $message->conversation_id, JsonResponse::HTTP_FORBIDDEN);

        $user = $request->user();
        // User cannot read a message two times.
        if ($message->users()->where('id', $user->id)->exists()) {
            abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $message->users()->attach($user);

        Notification::send($conversation->users, new MessageRead($message, $user));

        return response()->json($message, JsonResponse::HTTP_CREATED);
    }
}
