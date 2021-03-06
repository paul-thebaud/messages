<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends AbstractController
{
    /**
     * Fetch the notifications.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     */
    public function index(Request $request): JsonResponse
    {
        /** @todo Pagination. */
        return response()->json($request->user()->notifications());
    }

    /**
     * Update the notification.
     *
     * @param DatabaseNotification $notification The notification to update.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function update(DatabaseNotification $notification): JsonResponse
    {
        $this->authorize('update', $notification);

        $notification->markAsRead();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Delete the notification.
     *
     * @param DatabaseNotification $notification The notification to delete.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws Exception If the model does not exists.
     */
    public function destroy(DatabaseNotification $notification): JsonResponse
    {
        $this->authorize('delete', $notification);

        $notification->delete();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
