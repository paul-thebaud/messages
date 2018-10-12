<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Lcobucci\JWT\Parser;

/**
 * Class AuthController.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class AuthController extends AbstractController
{
    /**
     * @var string[] OAUTH_DRIVERS All available OAuth2 drivers.
     */
    public const OAUTH_DRIVERS = ['google'];

    /**
     * Register a new user.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'username' => 'required|string|min:4|max:60|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
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
     * Get the redirect URL for a OAuth2 driver.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     */
    public function redirect(Request $request): JsonResponse
    {
        $this->validate($request, [
            'driver' => 'required|in:%s' . implode(',', self::OAUTH_DRIVERS),
        ]);
        return response()->json([
            'url' => Socialite::with($request->input('driver'))
                ->stateless()
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    /**
     * Authenticate the user with the given driver.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     *
     * @throws ValidationException If the request is invalid.
     */
    public function authenticate(Request $request): JsonResponse
    {
        $this->validate($request, [
            'driver'    => 'required|in:password,%s' . implode(',', self::OAUTH_DRIVERS),
            'auth_code' => 'required_unless:driver,password|string',
            'username'  => 'required_unless:driver,password|string|min:4|max:60',
            'email'     => 'required_if:driver,password',
            'password'  => 'required_if:driver,password',
        ]);

        /** @var User $user */
        $user = null;
        // For password driver (basic authentication).
        if ('password' === $request->input('driver')) {
            // Attempt an authentication with credentials.
            if (false === Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'error'   => 'invalid_grant',
                    'message' => 'The user credentials were incorrect.'
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }
            $user = Auth::user();
            // Check that account is validated.
            if (null === $user->email_verified_at) {
                return response()->json([
                    'error'   => 'not_validated_account',
                    'message' => 'Account not validated.'
                ], JsonResponse::HTTP_FORBIDDEN);
            }
        } else {
            // For OAuth2 drivers.
            try {
                /** @var AbstractProvider $provider */
                $provider = Socialite::with($request->input('driver'));
                /** @var AbstractUser $providerUser */
                $providerUser = Socialite::with($request->input('driver'))->userFromToken(
                    $provider->getAccessTokenResponse($request->input('auth_code'))['access_token']
                );
                // Find or create the user.
                $user = User::query()->firstOrCreate(
                    ['email' => $providerUser->getEmail()],
                    ['username' => $request->input('username', sprintf('user_', str_random(10)))]
                );
                // Validate the user if it is not.
                if ($user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }
            } catch (Exception $exception) {
                return response()->json([
                    'error'   => 'invalid_grant',
                    'message' => 'The authentication code or something else is invalid.'
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }
        }

        return response()->json([
            'access_token' => $user->createToken(
                $request->input('name', sprintf('[%s-grant] Auto-generated API Key', $request->input('driver')))
            )->accessToken
        ]);
    }

    /**
     * Unauthenticate the user by destroying his token.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     */
    public function unauthenticate(Request $request): JsonResponse
    {
        // Destroy the token.
        $accessToken       = $request->bearerToken();
        $parsedAccessToken = (new Parser())->parse($accessToken)->getHeader('jti');
        $request->user()->tokens->find($parsedAccessToken)->revoke();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
