<?php

namespace Tests\Unit\Auth;

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
     * @group failing
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
     * @group failing
     */
    public function cant_generate_token_for_invalid_user()
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
            'email' => 'unknown@example.com',
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                'email' => 'Unknown user.',
            ],
        ]);
    }

    /**
     * Generate password reset token.
     *
     * @test
     *
     * @group failing
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

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token',
        ]);
    }

    /**
     * Can't reset user password without required scope.
     *
     * @test
     *
     * @group failing
     */
    public function cant_reset_password_if_not_authorized()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $token = str_random(60);

        DB::table('password_resets')->insert([
            'email' => 'john@example.com',
            'token' => bcrypt($token),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $token = $this->getClientToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/password/reset', [
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
     * @group failing
     */
    public function cant_reset_password_with_invalid_credentials()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $token = str_random(60);

        DB::table('password_resets')->insert([
            'email' => 'john@example.com',
            'token' => bcrypt($token),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $token = $this->getClientToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/password/reset', [
            'email' => 'unknown@example.com',
            'token' => $token,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Can't reset user password with an expired token.
     *
     * @test
     *
     * @group failing
     */
    public function can_reset_password_with_expired_token()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $token = str_random(60);

        $validity = config('app.password_reset_lifetime', 86400);

        $now = date('Y-m-d H:i:s');
        $current_timestamp = strtotime($now);
        $then = date('Y-m-d H:i:s', ($current_timestamp - $validity));

        DB::table('password_resets')->insert([
            'email' => 'john@example.com',
            'token' => bcrypt($token),
            'created_at' => $then,
        ]);

        $token = $this->getClientToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/password/reset', [
            'email' => 'jdoe@example.com',
            'token' => $token,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Can reset user password.
     *
     * @test
     *
     * @group failing
     */
    public function can_reset_password()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
        ]);

        $token = str_random(60);

        DB::table('password_resets')->insert([
            'email' => 'john@example.com',
            'token' => bcrypt($token),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $token = $this->getClientToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/password/reset', [
            'email' => 'jdoe@example.com',
            'token' => $token,
        ]);

        $response->assertStatus(200);
    }
}
