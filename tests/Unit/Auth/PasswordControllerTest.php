<?php

namespace Tests\Unit\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Can't generate password reset token without required scope.
     *
     * @test
     *
     * @group passing
     */
    public function cant_generate_token_if_not_authorized()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $token = $this->getClientToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/password/token', [
            'email' => 'jdoe@example.com',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Can't generate password reset token for an invalid user.
     *
     * @test
     *
     * @group passing
     */
    public function cant_generate_token_for_invalid_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $scopes = ['reset-password'];

        $token = $this->getClientToken($scopes);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/password/token', [
            'email' => 'unknown@example.com',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'error' => [
                'email',
            ],
        ]);

        $response->assertJson([
            'error' => [
                'email' => [
                    'The selected email is invalid.',
                ],
            ],
        ]);
    }

    /**
     * Generate password reset token.
     *
     * @test
     *
     * @group passing
     */
    public function can_generate_token()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $scopes = ['reset-password'];

        $token = $this->getClientToken($scopes);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/password/token', [
            'email' => 'jdoe@example.com',
        ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => 'jdoe@example.com',
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'token',
        ]);
    }

    /**
     * Can't reset user password without required scope.
     *
     * @test
     *
     * @group passing
     */
    public function cant_reset_password_if_not_authorized()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $token = str_random(60);

        DB::table('password_resets')->insert([
            'email' => 'jdoe@example.com',
            'token' => bcrypt($token),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $token = $this->getClientToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('PUT', 'api/v1/auth/password/reset', [
            'email' => 'jdoe@example.com',
            'token' => $token,
        ]);

        $response->assertStatus(403);
    }

    /**
     * Can't reset user password with invalid credentials.
     *
     * @test
     *
     * @group passing
     */
    public function cant_reset_password_with_invalid_credentials()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $reset_token = str_random(60);

        DB::table('password_resets')->insert([
            'email' => 'jdoe@example.com',
            'token' => bcrypt($reset_token),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $scopes = ['reset-password'];

        $auth_token = $this->getClientToken($scopes);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$auth_token['access_token'],
        ])->json('PUT', 'api/v1/auth/password/reset', [
            'token' => $reset_token,
            'email' => 'unknown@example.com',
            'password' => 'Xc4qF!Ek',
            'password_confirmation' => '5YQ-9TjO',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'error' => [
                'email',
                'password',
            ],
        ]);
    }

    /**
     * Can't reset user password with an expired token.
     *
     * @test
     *
     * @group passing
     */
    public function can_reset_password_with_expired_token()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $reset_token = str_random(60);

        $validity = config('app.password_reset_lifetime', 60) * 60;

        $now = date('Y-m-d H:i:s');
        $current_timestamp = strtotime($now);
        $then = date('Y-m-d H:i:s', ($current_timestamp - $validity));

        DB::table('password_resets')->insert([
            'email' => 'jdoe@example.com',
            'token' => bcrypt($reset_token),
            'created_at' => $then,
        ]);

        $scopes = ['reset-password'];

        $auth_token = $this->getClientToken($scopes);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$auth_token['access_token'],
        ])->json('PUT', 'api/v1/auth/password/reset', [
            'token' => $reset_token,
            'email' => 'jdoe@example.com',
            'password' => 'Xc4qF!Ek',
            'password_confirmation' => 'Xc4qF!Ek',
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'error' => [
                'token' => [
                    'Expired token.',
                ],
            ],
        ]);
    }

    /**
     * Can reset user password.
     *
     * @test
     *
     * @group passing
     */
    public function can_reset_password()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
            'password' => 'C9mWvb+h',
        ]);

        $reset_token = str_random(60);

        DB::table('password_resets')->insert([
            'email' => 'jdoe@example.com',
            'token' => bcrypt($reset_token),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $scopes = ['reset-password'];

        $auth_token = $this->getClientToken($scopes);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$auth_token['access_token'],
        ])->json('PUT', 'api/v1/auth/password/reset', [
            'token' => $reset_token,
            'email' => 'jdoe@example.com',
            'password' => 'Xc4qF!Ek',
            'password_confirmation' => 'Xc4qF!Ek',
        ]);

        $user = User::where('email', 'jdoe@example.com')->first();

        $this->assertTrue(password_verify('Xc4qF!Ek', $user->password));

        $this->assertDatabaseMissing('password_resets', [
            'email' => 'jdoe@example.com',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
        ]);
    }
}
