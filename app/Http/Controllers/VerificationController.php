<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class VerificationController.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class VerificationController extends AbstractController
{
    /**
     * Verify the email from a signed URL.
     *
     * @param Request $request The request.
     * @param User    $user    The user to validate.
     *
     * @return RedirectResponse The redirect response to index.
     */
    public function __invoke(Request $request, User $user): RedirectResponse
    {
        if (false === $request->hasValidSignature()) {
            abort(401);
        }
        $user->markEmailAsVerified();
        return redirect('/login?verified', RedirectResponse::HTTP_FOUND);
    }
}
