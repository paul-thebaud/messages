<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends AbstractController
{
    /**
     * Fetch the users.
     *
     * @return JsonResponse The response.
     */
    public function index(): JsonResponse
    {
        /** @todo Pagination. */
        /** @todo Search. */
        return response()->json(User::all());
    }

    /**
     * Fetch the user.
     *
     * @param User $user The user to show.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function show(User $user): JsonResponse
    {
        $this->authorize('show', $user);

        return response()->json($user);
    }

    /**
     * Fetch the user's friends.
     *
     * @param User $user The user to fetch friends from.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function friends(User $user): JsonResponse
    {
        $this->authorize('show', $user);

        return response()->json($user->friends());
    }

    /**
     * Update the user.
     *
     * @param Request $request The request.
     * @param User    $user    The user to update.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     * @throws ValidationException If the request is invalid.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'username' => 'required|string|min:4|max:60|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('username'));

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Delete the user.
     *
     * @param User $user The user to delete.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function delete(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
