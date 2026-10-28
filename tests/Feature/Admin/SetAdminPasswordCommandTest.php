<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\Hash;

class SetAdminPasswordCommandTest extends AdminTestCase
{
    public function test_sets_a_hashed_password_after_two_matching_hidden_prompts(): void
    {
        $admin = $this->admin(['email' => 'owner@example.com']);

        $this->artisan('admin:password', ['email' => 'owner@example.com'])
            ->expectsQuestion('New password (at least 10 characters, not shown as you type)', 'a-long-new-password')
            ->expectsQuestion('Type it again', 'a-long-new-password')
            ->expectsOutput('Password updated for owner@example.com.')
            ->assertSuccessful();

        $this->assertTrue(Hash::check('a-long-new-password', $admin->fresh()->password));
    }

    public function test_mismatched_passwords_change_nothing(): void
    {
        $admin = $this->admin(['email' => 'owner@example.com']);
        $before = $admin->password;

        $this->artisan('admin:password', ['email' => 'owner@example.com'])
            ->expectsQuestion('New password (at least 10 characters, not shown as you type)', 'a-long-new-password')
            ->expectsQuestion('Type it again', 'something-else-entirely')
            ->assertFailed();

        $this->assertSame($before, $admin->fresh()->password);
    }

    public function test_short_password_is_refused(): void
    {
        $this->admin(['email' => 'owner@example.com']);

        $this->artisan('admin:password', ['email' => 'owner@example.com'])
            ->expectsQuestion('New password (at least 10 characters, not shown as you type)', 'short')
            ->assertFailed();
    }

    public function test_unknown_email_is_reported(): void
    {
        $this->artisan('admin:password', ['email' => 'nobody@example.com'])
            ->expectsOutput('No user has that email.')
            ->assertFailed();
    }
}
