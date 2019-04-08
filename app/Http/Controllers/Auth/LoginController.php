<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
    }

    /**
     * Authenticate user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'scope' => 'sometimes|string',
        ]);

        $client = get_auth_client($request);

        // If isn't a password client; don't issue access token...

        if (! $client->password_client) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! password_verify($request->password, $user->password)) {
            return response()->json(['errors' => ['email' => 'Wrong email or password.']], 422);
        }

        $parameters = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => $request->scope,
        ];

        $headers = [
            'Accept' => 'application/json',
        ];

        $user['token'] = $this->getToken('POST', 'oauth/token', $parameters, $headers);

        return response()->json($user);
    }

    /**
     * Log the user out of the api.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();

        $token->revoke();

        // Revoke refresh token
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)
            ->update([
                'revoked' => true,
            ]);

        return response()->json(null, 204);
    }

    /**
     * Request for token.
     *
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @param array $headers
     *
     * @return null|array token
     */
    private function getToken($method, $uri, $parameters = [], $headers = [])
    {
        // Symfony\Component\HttpFoundation\Request@create
        $request = Request::create($uri, $method, $parameters);
        $request->headers->add($headers);

        try {
            $response = app()->handle($request);

            return json_decode((string) $response->getContent(), true);
        } catch (\Exception $e) {
            Log::error(json_encode([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]));

            return null;
        }
    }
}
