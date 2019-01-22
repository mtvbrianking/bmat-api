<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['store', 'authenticate']);
    }

    /**
     * Authenticate user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(Request $request)
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

        if ($user && password_verify($request->password, $user->password)) {

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

        return response()->json(['errors' => ['email' => 'Wrong email or password.']], 422);
    }

    /**
     * Log the user out of the api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        $access_token = $request->user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $access_token->id)
            ->update([
                'revoked' => true
            ]);

        $access_token->revoke();
        return response()->json(null, 204);
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::withTrashed()->get();

        return response()->json(['users' => $users]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(null, 501);
    }

    /**
     * Store a newly created user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'email_verified_at' => 'sometimes|date_format:Y-m-d H:i:s',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json($user, 201);
    }

    /**
     * Display the specified user.
     *
     * @param string $id User ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::withTrashed()->find($id);

        if (! $user) {
            return response()->json(['error' => 'Unknown user'], 404);
        }

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param string $id User ID
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response(null, 501);
    }

    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $id User ID
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['error' => 'Unknown user'], 404);
        }

        $this->validate($request, [
            'name' => 'sometimes|max:100|unique:users,name,'.$user->id,
            'email' => 'sometimes|email|max:255|unique:users,email,'.$user->id,
            'email_verified_at' => 'sometimes|date_format:Y-m-d H:i:s',
        ]);

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->email_verified_at = $request->input('email_verified_at', $user->email_verified_at);

        $user->save();

        return response()->json($user);
    }

    /**
     * Update the specified user profile.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $id User ID
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['error' => 'Unknown user'], 404);
        }

        $this->validate($request, [
            'name' => 'sometimes|max:100|unique:users,name,'.$user->id,
            'email' => 'sometimes|email|max:255|unique:users,email,'.$user->id,
            'password' => $request->has('password') != null ? 'sometimes|required|min:6|confirmed' : '',
            'current_password' => 'required_with:password',
        ]);

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->email_verified_at = $request->input('email_verified_at', $user->email_verified_at);
        if ($request->has('password')) {
            if (! password_verify($request->current_password, $user->password)) {
                return response()->json(['errors' => ['current_password' => ['Wrong password']]], 422);
            }

            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json($user);
    }

    /**
     * Remove user.
     *
     * @param string $id User ID
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception If no primary key defined on model.
     */
    public function trash($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['error' => 'Unknown user'], 404);
        }

        $user->delete();

        return response()->json($user);
    }

    /**
     * Restore user.
     *
     * @param string $id User ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);

        if (! $user) {
            return response()->json(['error' => 'Unknown user'], 404);
        }

        $user->restore();

        return response()->json($user);
    }

    /**
     * Permanently remove user from storage.
     *
     * @param string $id User ID
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->find($id);

        if (! $user) {
            return response()->json(['error' => 'Unknown user'], 404);
        }

        $user->forceDelete();

        return response(null, 204);
    }

    /**
     * Request for token.
     *
     * @param $method
     * @param $uri
     * @param array $parameters
     * @param array $headers
     * @return null|\mixed token
     */
    private function getToken($method, $uri, $parameters = [], $headers = [])
    {
        $request = Request::create($uri, $method, $parameters, [], [], [], null);
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
