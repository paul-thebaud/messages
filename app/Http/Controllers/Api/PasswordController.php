<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ForgotPassword;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordController extends AbstractController
{
    /**
     * Request a password reset link.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     */
    public function forgot(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
        ]);

        $user          = User::query()->where('email', $request->input('email'))->first();
        $passwordReset = PasswordReset::query()->forceCreate([
            'user_id' => $user->id,
        ]);
        $user->notify(new ForgotPassword($passwordReset));

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Reset a password from a password reset request token.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     * @throws Exception If the model does not exists.
     */
    public function reset(Request $request): JsonResponse
    {
        $this->validate($request, [
            'token'    => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        /** @var PasswordReset $passwordReset */
        $passwordReset = PasswordReset::query()
            ->where('token', $request->input('token'))
            ->where('expire_at', '>', now()->toDateTimeString())
            ->first();

        if (null === $passwordReset) {
            throw ValidationException::withMessages([
                'token' => ['Expired reset link, please request a new link.'],
            ]);
        }

        $user           = $passwordReset->user;
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $passwordReset->delete();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
