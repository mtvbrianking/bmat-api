<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Get users.
     * @test
     * @group current
     */
    public function getUsers()
    {
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($user, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->json('GET', 'api/users', []);

        $response->assertStatus(200);
    }

    /**
     * Visit create user.
     * @test
     * @group current
     */
    public function visitCreateUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', 'api/users/create');

        $response->assertStatus(501);
    }

    /**
     * Register user.
     * @test
     * @group current
     */
    public function createUser()
    {
        $this->withoutMiddleware(\Laravel\Passport\Http\Middleware\CheckClientCredentials::class);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('POST', 'api/users', [
                'name' => 'John Doe',
                'email' => 'jdoe@example.com',
                'password' => '!B>z5RJ%dUE$F52_',
                'password_confirmation' => '!B>z5RJ%dUE$F52_',
            ]);

        $response->assertStatus(201);
    }

    /**
     * User details
     * @test
     * @group current
     */
    public function getUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', 'api/users/' . $user->id);

        $response->assertStatus(200);
    }

    /**
     * Authenticate user
     * @test
     * @group current
     */
    public function authenticateUser()
    {
         // $this->withoutExceptionHandling();

        // Laravel Passport Issues: #680, #514
        $this->withoutMiddleware(\Laravel\Passport\Http\Middleware\CheckClientCredentials::class);

        $user = factory(User::class)->create([
            'email' => 'jdoe@example.com',
            'password' => Hash::make('gJrFhC2B-!Y!4CTk'),
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('POST', 'api/users/auth', [
                'email' => 'jdoe@example.com',
                'password' => 'gJrFhC2B-!Y!4CTk',
            ]);

        $response->assertStatus(200);
    }

    /**
     * Visit edit user.
     * @test
     * @group current
     */
    public function visitEditUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', 'api/users/' . $user->id . '/edit');

        $response->assertStatus(501);
    }

    /**
     * Update user details
     * @test
     * @group current
     */
    public function updateUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('PUT', 'api/users/' . $user->id, [
                'name' => 'John Doe',
                'email' => 'jdoe@example.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => '!B>z5RJ%dUE$F52_',
                'password_confirmation' => '!B>z5RJ%dUE$F52_',
            ]);

        $response->assertStatus(200);
    }

    /**
     * Update user profile
     * @test
     * @group current
     */
    public function updateUserProfile()
    {
        $user = factory(User::class)->create([
            'password' => 'gJrFhC2B-!Y!4CTk',
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('PUT', 'api/users/' . $user->id . '/profile', [
                'name' => 'John Doe',
                'email' => 'jdoe@example.com',
                'current_password' => 'gJrFhC2B-!Y!4CTk',
                'password' => '!B>z5RJ%dUE$F52_',
                'password_confirmation' => '!B>z5RJ%dUE$F52_',
            ]);

        $response->assertStatus(200);
    }

    /**
     * Delete user
     * Temporarily
     * @test
     * @group current
     */
    public function removeUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('PUT', 'api/users/' . $user->id . '/trash');

        $response->assertStatus(200);
    }

    /**
     * Restore user
     * @test
     * @group current
     */
    public function restoreUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('PUT', 'api/users/' . $user->id . '/restore');

        $response->assertStatus(404);
    }

    /**
     * Restore trashed user
     * @test
     * @group current
     */
    public function restoreTrashedUser()
    {
        $user = factory(User::class)->create();

        $user->delete();

        $response = $this->actingAs($user, 'api')
            ->json('PUT', 'api/users/' . $user->id . '/restore');

        $response->assertStatus(200);
    }

    /**
     * Delete user
     * Permanently
     * @test
     * @group current
     */
    public function deleteUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', 'api/users/' . $user->id);

        $response->assertStatus(204);
    }

}
