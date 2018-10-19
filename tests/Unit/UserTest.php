<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get users.
     *
     * @test
     * @group passing
     */
    public function getUsers()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/v1/users', []);

        $response->assertStatus(200);
    }

    /**
     * Visit create user.
     *
     * @test
     * @group passing
     */
    public function visitCreateUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('GET', 'api/v1/users/create');

        $response->assertStatus(501);
    }

    /**
     * Register user.
     *
     * @test
     * @group passing
     */
    public function createUser()
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
    public function getUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('GET', 'api/v1/users/'.$user->id);

        $response->assertStatus(200);
    }

    /**
     * Authenticate user.
     *
     * @test
     * @group passing
     */
    public function authenticateUser()
    {
        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
            'password' => Hash::make('gJrFhC2B-!Y!4CTk'),
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->getClientToken(),
        ])->actingAs($user, 'api')->json('POST', 'api/v1/users/auth', [
            'email' => 'jdoe@example.com',
            'password' => 'gJrFhC2B-!Y!4CTk',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Visit edit user.
     *
     * @test
     * @group passing
     */
    public function visitEditUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('GET', 'api/v1/users/'.$user->id.'/edit');

        $response->assertStatus(501);
    }

    /**
     * Update user details.
     *
     * @test
     * @group passing
     */
    public function updateUser()
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
    public function updateUserProfile()
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
    public function removeUser()
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
    public function restoreUser()
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
    public function restoreTrashedUser()
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
    public function deleteUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('DELETE', 'api/v1/users/'.$user->id);

        $response->assertStatus(204);
    }
}
