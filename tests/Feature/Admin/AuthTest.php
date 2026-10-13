<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

class AuthTest extends AdminTestCase
{
    public function test_guest_is_redirected_from_admin_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get('/admin/services')->assertRedirect(route('admin.login'));
    }

    public function test_login_page_renders_with_noindex(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('noindex, nofollow', false);
    }

    public function test_user_can_log_in_and_out(): void
    {
        $user = $this->admin(['email' => 'owner@studio.test']);

        $this->post(route('admin.login.store'), ['email' => 'owner@studio.test', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Launch checklist');

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->admin(['email' => 'owner@studio.test']);

        $this->from(route('admin.login'))
            ->post(route('admin.login.store'), ['email' => 'owner@studio.test', 'password' => 'nope'])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_attempts(): void
    {
        $this->admin(['email' => 'owner@studio.test']);

        foreach (range(1, 5) as $attempt) {
            $this->post(route('admin.login.store'), ['email' => 'owner@studio.test', 'password' => 'wrong-'.$attempt]);
        }

        // Even the correct password is refused while locked out.
        $this->post(route('admin.login.store'), ['email' => 'owner@studio.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many', session('errors')->first('email'));
        $this->assertTrue(RateLimiter::tooManyAttempts('admin-login|owner@studio.test|127.0.0.1', 5));
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $this->editor(['email' => 'former@studio.test', 'is_active' => false]);

        $this->post(route('admin.login.store'), ['email' => 'former@studio.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_deactivated_user_is_logged_out_on_next_request(): void
    {
        $user = $this->editor();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();

        $user->update(['is_active' => false]);

        $this->actingAs($user->fresh())->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
