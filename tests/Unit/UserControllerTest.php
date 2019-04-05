<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get users.
     *
     * @test
     * @group passing
     */
    public function can_get_users()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/v1/users', []);

        $response->assertStatus(200);
    }

    /**
     * Register user.
     *
     * @test
     * @group passing
     */
    public function can_register_user()
    {
        $user = factory(User::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->getClientToken(),
        ])->actingAs($user, 'api')->json('POST', 'api/v1/users', [
            'name' => 'John Doe',
            'email' => 'jdoe@example.com',
            'password' => '!B>z5RJ%dUE$F52_',
            'password_confirmation' => '!B>z5RJ%dUE$F52_',
        ]);

        $response->assertStatus(201);
    }

    /**
     * User details.
     *
     * @test
     * @group passing
     */
    public function can_get_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('GET', 'api/v1/users/'.$user->id);

        $response->assertStatus(200);
    }

    /**
     * Can obtain password grant type token.
     * This will be used to log into the api (authenticate user) - literally.
     * @test
     * @group passing
     */
    public function can_obtain_password_grant_type_token()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
            'password' => Hash::make('gJrFhC2B-!Y!4CTk'),
        ]);

        $this->createPasswordClient();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->call('POST', 'oauth/token', [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $user->email,
            'password' => 'gJrFhC2B-!Y!4CTk',
            'scope' => '*',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Update user details.
     *
     * @test
     * @group passing
     */
    public function can_update_user_details()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('PUT', 'api/v1/users/'.$user->id, [
            'name' => 'John Doe',
            'email' => 'jdoe@example.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => '!B>z5RJ%dUE$F52_',
            'password_confirmation' => '!B>z5RJ%dUE$F52_',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Update user profile.
     *
     * @test
     * @group passing
     */
    public function can_update_user_profile()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('gJrFhC2B-!Y!4CTk'),
        ]);

        $response = $this->actingAs($user, 'api')->json('PUT', 'api/v1/users/'.$user->id.'/profile', [
            'name' => 'John Doe',
            'email' => 'jdoe@example.com',
            'current_password' => 'gJrFhC2B-!Y!4CTk',
            'password' => '!B>z5RJ%dUE$F52_',
            'password_confirmation' => '!B>z5RJ%dUE$F52_',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Delete user - temporarily.
     *
     * @test
     * @group passing
     */
    public function can_delete_user_temporarily()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('PUT', 'api/v1/users/'.$user->id.'/trash');

        $response->assertStatus(200);
    }

    /**
     * Restore user.
     *
     * @test
     * @group passing
     */
    public function can_not_restore_non_deleted_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('PUT', 'api/v1/users/'.$user->id.'/restore');

        $response->assertStatus(404);
    }

    /**
     * Restore trashed user.
     *
     * @test
     * @group passing
     */
    public function can_restore_temporarily_deleted_user()
    {
        $user = factory(User::class)->create();

        $user->delete();

        $response = $this->actingAs($user, 'api')->json('PUT', 'api/v1/users/'.$user->id.'/restore');

        $response->assertStatus(200);
    }

    /**
     * Delete user - permanently.
     *
     * @test
     * @group passing
     */
    public function can_permanently_deleted_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('DELETE', 'api/v1/users/'.$user->id);

        $response->assertStatus(204);
    }
}
