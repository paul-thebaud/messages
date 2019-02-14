<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends AbstractController
{
    /**
     * Fetch the users.
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
        /** @todo Pagination. */
        /** @todo Search. */
        return response()->json(
            User::query()
                ->where('id', '<>', $request->user()->id)
                ->when($request->input('search'), function (Builder $query) use ($request) {
                    $query->where('username', 'like', sprintf('%%%s%%', $request->input('search')));
                })
                ->get()
        );
    }

    /**
     * Create a user.
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
            'username' => 'required|string|min:4|max:60|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = User::query()->create([
            'username' => $request->input('username'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        $user->notify(new VerifyEmail($user));

        return response()->json('', JsonResponse::HTTP_CREATED);
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
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (null !== $request->input('password')) {
            $request->merge([
                'password' => bcrypt($request->input('password')),
            ]);
        }

        $user->update($request->only('username', 'password'));

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
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
