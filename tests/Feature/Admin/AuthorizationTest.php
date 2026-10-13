<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;

class AuthorizationTest extends AdminTestCase
{
    public function test_editor_cannot_access_admin_only_areas(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.users.create'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.settings.edit'))->assertForbidden();
        $this->actingAs($editor)->put(route('admin.settings.update'), ['site_name' => 'Hacked'])->assertForbidden();
        $this->actingAs($editor)->get(route('admin.redirects.index'))->assertForbidden();

        $this->assertNull(Setting::get('site_name'));
    }

    public function test_editor_can_manage_content(): void
    {
        $editor = $this->editor();

        foreach (['services', 'projects', 'pages', 'blog-posts', 'media', 'inquiries', 'faqs', 'testimonials', 'navigation', 'service-areas'] as $module) {
            $this->actingAs($editor)->get(route("admin.{$module}.index"))->assertOk();
        }
    }

    public function test_admin_cannot_delete_or_deactivate_themselves(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertForbidden();

        $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name, 'email' => $admin->email, 'role' => 'admin', 'is_active' => '0',
        ])->assertSessionHasErrors('is_active');

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_admin_can_create_a_user_with_a_strong_password(): void
    {
        $this->actingAs($this->admin())->post(route('admin.users.store'), [
            'name' => 'New Editor', 'email' => 'editor@studio.test', 'role' => 'editor', 'is_active' => '1',
            'password' => 'short1', 'password_confirmation' => 'short1',
        ])->assertSessionHasErrors('password');

        $this->actingAs($this->admin())->post(route('admin.users.store'), [
            'name' => 'New Editor', 'email' => 'editor@studio.test', 'role' => 'editor', 'is_active' => '1',
            'password' => 'longer-pass-123', 'password_confirmation' => 'longer-pass-123',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'editor@studio.test', 'role' => 'editor']);
    }

    public function test_settings_are_saved_with_real_booleans(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.update'), [
            'site_name' => 'Maison Linen',
            'phone' => '+971 50 123 4567',
            'local_business_enabled' => '1',
            'process_steps' => [['title' => 'Consultation', 'text' => 'We listen.', 'icon' => 'chat', 'media_id' => '']],
        ])->assertRedirect(route('admin.settings.edit'));

        $this->assertSame('Maison Linen', Setting::get('site_name'));
        $this->assertTrue(Setting::get('local_business_enabled'));
        $this->assertFalse(Setting::all_values()['whatsapp_float_enabled']);
        $this->assertSame('Consultation', Setting::get('process_steps')[0]['title']);
    }
}
