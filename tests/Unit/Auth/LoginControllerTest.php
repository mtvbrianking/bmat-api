<?php

namespace Tests\Unit\Auth;

use App\User;
use Tests\TestCase;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Can't authenticate.
     *
     * Without a password grant type client.
     *
     * @test
     * @group passing
     */
    public function cant_authenticate_user_without_a_password_grant_type()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
            'password' => Hash::make('gJrFhC2B-!Y!4CTk'),
        ]);

        $token = $this->getClientToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/login', [
            'email' => 'jdoe@example.com',
            'password' => 'gJrFhC2B-!Y!4CTk',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Can't authenticate.
     *
     * With invalid user credentials.
     *
     * @test
     * @group passing
     */
    public function cant_authenticate_user_with_invlaid_user_credentials()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
            'password' => Hash::make('gJrFhC2B-!Y!4CTk'),
        ]);

        $token = $this->getPasswordToken($user->email, 'gJrFhC2B-!Y!4CTk');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->actingAs($user, 'api')->json('POST', 'api/v1/auth/login', [
            'email' => 'jdoe@example.com',
            'password' => 'GgG1dqyX185vst2V',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Log in.
     *
     * Using a password grant type client, with valid user credentials.
     *
     * @test
     * @group passing
     */
    public function can_authenticate_user()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
            'password' => Hash::make('gJrFhC2B-!Y!4CTk'),
        ]);

        $token = $this->getPasswordToken($user->email, 'gJrFhC2B-!Y!4CTk');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->actingAs($user, 'api')->json('POST', 'api/v1/auth/login', [
            'email' => 'jdoe@example.com',
            'password' => 'gJrFhC2B-!Y!4CTk',
        ]);

        $response->assertStatus(200);
    }

    /**
     * User logout.
     *
     * @test
     * @group passing
     */
    public function can_log_out()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
            'password' => Hash::make('gJrFhC2B-!Y!4CTk'),
        ]);

        $token = $this->getPasswordToken($user->email, 'gJrFhC2B-!Y!4CTk');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ])->json('POST', 'api/v1/auth/logout', []);

        $tokenId = (new Parser())->parse($token['access_token'])->getHeader('jti');

        $this->assertDatabaseHas('oauth_access_tokens', [
            'id' => $tokenId,
            'revoked' => 1,
        ]);

        $this->assertDatabaseHas('oauth_refresh_tokens', [
            'access_token_id' => $tokenId,
            'revoked' => 1,
        ]);

        $response->assertStatus(204);
    }
}
