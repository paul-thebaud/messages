<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ForgotPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Class PasswordResetController.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class PasswordResetController extends AbstractController
{
    /**
     * Allow to send a reset password notification.
     *
     * @param Request $request The request.
     *
     * @return RedirectResponse|View The forgot password view or a redirect.
     */
    public function forgot(Request $request)
    {
        // Only display the view on GET method.
        if ($request->isMethod(Request::METHOD_GET)) {
            return view('forgot_password');
        }

        $validator = Validator::make($request->only('email'), [
            'email' => 'required|email|exists:users',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
        /** @var User $user */
        $user          = User::query()->where('email', $request->input('email'))->first();
        $passwordReset = PasswordReset::query()->forceCreate([
            'user_id' => $user->id,
        ]);
        $user->notify(new ForgotPassword($passwordReset));

        return redirect()
            ->back()
            ->with('message', 'Password reset mail sent!');
    }

    /**
     * Allow to reset the password.
     *
     * @param Request $request The request.
     * @param string  $token   The token.
     *
     * @return RedirectResponse|View The reset password view or a redirect.
     */
    public function reset(Request $request, string $token)
    {
        /** @var PasswordReset $passwordReset */
        $passwordReset = PasswordReset::query()->where('token', $token)->firstOrFail();

        // Only display the view on GET method.
        if ($request->isMethod(Request::METHOD_GET)) {
            return view('reset_password');
        }

        $validator = Validator::make($request->only('email'), [
            'password' => 'required|string|min:4|confirmed',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }
        $user           = $passwordReset->user;
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()
            ->back()
            ->with('message', 'Password reset!');
    }
}
