<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Can't vist home unauthenticated.
     *
     * @test
     *
     * @group passing
     */
    public function cant_visit_home_unauthenticated()
    {
        $this->get('/')->assertStatus(302)->assertRedirect(route('login'));
    }

    /**
     * Can vist home if authenticated.
     *
     * @test
     *
     * @group passing
     */
    public function can_visit_home()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    /**
     * API usage documentation.
     *
     * @test
     * @group passing
     */
    public function can_see_api_usage_documentation()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/usage');

        $response->assertStatus(200);
    }

    /**
     * Source code documentation.
     *
     * @test
     * @group passing
     */
    public function can_see_source_code_documentation()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/source');

        $response->assertStatus(302)->assertRedirect('/docs');
    }

    /**
     * Application routes.
     *
     * @test
     * @group passing
     */
    public function can_see_app_routes()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/routes');

        $response->assertStatus(200);
    }
}
