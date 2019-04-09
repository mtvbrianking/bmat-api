<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // $this->middleware('auth:api')->except([]);
    }

    /**
     * Generate reset token.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Remove existing token(s).
        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        $token = str_random(60);
        $token_hash = bcrypt($token);

        // Store reset token.
        DB::table('password_resets')->insert([
          'email' => $request->email,
          'token' => $token_hash,
          'created_at' => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'token' => $token,
        ], 201);
    }

    /**
     * Reset password.
     *
     * Illuminate\Auth\Passwords\PasswordBroker
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'token' => 'required|string',
                'email' => 'required|email|exists:users,email',
                'password' => [
                    'required',
                    'confirmed',
                    'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                ],
            ],
            [
                'password.regex' => 'Password must have at least 8 characters including; an upper case letter, a lower case letter, a number and, a symbol.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $password = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (! $password) {
            return response()->json(['error' => ['token' => ['Unknown token.']]], 422);
        }

        $is_valid = password_verify($request->token, $password->token);

        if (! $is_valid) {
            return response()->json(['error' => ['token' => ['Invalid token.']]], 422);
        }

        $life = Carbon::parse($password->created_at)->diffInSeconds(Carbon::now());

        if ($life > config('app.password_reset_lifetime', 60)) {
            return response()->json(['error' => ['token' => ['Expired token.']]], 422);
        }

        DB::beginTransaction();

        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        User::where('email', $request->email)
          ->update([
            'password' => bcrypt($request->password),
        ]);

        DB::commit();

        return response()->json(['status' => 'Password has been reset.'], 200);
    }
}
