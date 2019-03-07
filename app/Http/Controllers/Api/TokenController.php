<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Facades\Agent;
use Laravel\Passport\Token;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Torann\GeoIP\Facades\GeoIP;
use Torann\GeoIP\Location;

/**
 * Class TokenController.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class TokenController extends AbstractController
{
    /**
     * @var string[] OAUTH_DRIVERS All available OAuth2 drivers.
     */
    public const OAUTH_DRIVERS = ['google', 'facebook'];

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
            'driver' => 'required|in:' . implode(',', self::OAUTH_DRIVERS),
        ]);
        return response()->json([
            'url' => Socialite::with($request->input('driver'))
                ->stateless()
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    /**
     * Fetch the tokens.
     *
     * @param Request $request The request.
     *
     * @return JsonResponse The response.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json($request->user()->tokens);
    }

    /**
     * Create a token using the given driver.
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
            'driver'    => 'required|in:password,' . implode(',', self::OAUTH_DRIVERS),
            'auth_code' => 'required_unless:driver,password|string',
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
                    'errors' => [
                        'email' => ['The user credentials were incorrect.']
                    ],
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            $user = Auth::user();
            // Check that account is validated.
            if (null === $user->email_verified_at) {
                return response()->json([
                    'errors' => [
                        'email' => ['The email address must be validated.']
                    ],
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
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
                    ['username' => $providerUser->getEmail()]
                );
                // Validate the user if it is not.
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }
            } catch (Exception $exception) {
                Log::debug('exception thrown during OAuth2 process', [
                    $exception->getMessage(),
                    $exception->getFile(),
                    $exception->getLine(),
                    $exception->getTraceAsString(),
                ]);
                return response()->json([
                    'errors' => [
                        'email' => ['The authentication code is invalid.']
                    ],
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        /** @var Location $location */
        $location = GeoIP::getLocation();

        // Create the token and save the used driver and
        // current user agent in the token's name.
        $token = $user->createToken(
            $request->input('name', sprintf(
                "Token generated with %s grant\nLocation: %s\nDevice: %s\nPlatform: %s\nBrowser: %s\n",
                $request->input('driver'),
                sprintf('%s - %s, %s', $location['ip'], $location['city'], $location['country']),
                $this->getAgentInformation('device'),
                $this->getAgentInformation('platform'),
                $this->getAgentInformation('browser')
            ))
        );
        return response()->json([
            'access_token' => $token->accessToken,
            'token'        => $token->token,
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Delete the given token.
     *
     * @param Token $token The token to delete.
     *
     * @return JsonResponse The response.
     *
     * @throws AuthorizationException If the user cannot perform this action.
     */
    public function destroy(Token $token): JsonResponse
    {
        $this->authorize('delete', $token);

        $token->delete();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    private function getAgentInformation(string $part): string
    {
        $device = Agent::{$part}();
        return empty($device) ? 'Unknown' : sprintf('%s %s', $device, Agent::version($device));
    }
}
