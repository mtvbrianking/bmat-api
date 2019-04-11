<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailVerificationControllerTest extends TestCase
{
    use RefreshDatabase;

    // ..........

    protected function validVerificationVerifyRoute($id)
    {
        return URL::signedRoute('verification.verify', ['id' => $id]);
    }

    protected function invalidVerificationVerifyRoute($id)
    {
        return route('verification.verify', ['id' => $id]).'?signature=invalid-signature';
    }

    /**
     * @test
     * @group passing
     */
    public function cant_visit_email_verification_notice_when_unauthenticated()
    {
        $response = $this->get(route('verification.notice'));

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     * @group passing
     */
    public function cant_visit_email_verification_notice_when_already_verified()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertRedirect(route('home'));
    }

    /**
     * @test
     * @group passing
     */
    public function can_visit_email_verification_when_not_verified()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }

    // ..........

    /**
     * @test
     * @group passing
     *
     * @throws \Exception
     */
    public function cant_visit_email_verification_when_unauthenticated()
    {
        $user = factory(User::class)->create([
            'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'email_verified_at' => null,
        ]);

        $response = $this->get($this->validVerificationVerifyRoute($user->id));

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     * @group passing
     */
    public function cant_visit_email_verification_impersonating_other_users()
    {
        $user_1 = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $user_2 = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user_1)->get($this->validVerificationVerifyRoute($user_2->id));

        $response->assertForbidden();
        $this->assertFalse($user_2->fresh()->hasVerifiedEmail());
    }

    /**
     * @test
     * @group passing
     */
    public function cant_visit_email_verification_when_verified()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get($this->validVerificationVerifyRoute($user->id));

        $response->assertRedirect(route('home'));
    }

    /**
     * @test
     * @group passing
     */
    public function cant_verify_email_with_invalid_signature()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get($this->invalidVerificationVerifyRoute($user->id));

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group passing
     */
    public function can_verify_email_with_valid_signature()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get($this->validVerificationVerifyRoute($user->id));

        $response->assertRedirect(route('home'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    // ..........

    /**
     * @test
     * @group passing
     */
    public function cant_request_resend_email_verification_link_when_unauthenticated()
    {
        $response = $this->get(route('verification.resend'));

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     * @group passing
     */
    public function cant_visit_resend_email_verification_when_already_verified()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('verification.resend'));

        $response->assertRedirect(route('home'));
    }

    /**
     * @test
     * @group passing
     */
    public function can_request_resend_email_verification_link()
    {
        Notification::fake();
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->from(route('verification.notice'))
            ->get(route('verification.resend'));

        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertRedirect(route('verification.notice'));
    }
}
