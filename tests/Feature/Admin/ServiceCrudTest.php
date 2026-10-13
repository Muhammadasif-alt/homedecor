<?php

namespace Tests\Feature\Admin;

use App\Models\Service;

class ServiceCrudTest extends AdminTestCase
{
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Curtains & Blinds',
            'slug' => '',
            'short_description' => 'Made-to-measure window treatments.',
            'hero_title' => 'Curtains that frame the view',
            'hero_description' => 'Tailored curtains and blinds for every room.',
            'intro' => '<h1>Soft light</h1><p>Hello</p><script>alert(1)</script>',
            'included' => [['title' => 'Measuring', 'text' => 'On-site measuring'], ['title' => '', 'text' => '']],
            'sections' => [['heading' => 'Fabrics', 'body' => '<p>Linen and velvet</p>', 'media_id' => '']],
            'is_published' => '1',
            'sort_order' => '3',
        ], $overrides);
    }

    public function test_admin_can_create_update_and_delete_a_service(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->get(route('admin.services.create'))->assertOk();

        $this->actingAs($admin)->post(route('admin.services.store'), $this->payload())
            ->assertSessionHasNoErrors();

        $service = Service::where('slug', 'curtains-blinds')->firstOrFail();
        $this->assertTrue($service->is_published);
        $this->assertFalse($service->is_featured);
        $this->assertStringNotContainsString('<script', $service->intro);
        $this->assertStringNotContainsString('<h1', $service->intro);
        $this->assertSame([['title' => 'Measuring', 'text' => 'On-site measuring']], $service->included);
        $this->assertCount(1, $service->sections);

        $this->actingAs($admin)->get(route('admin.services.edit', $service))->assertOk()->assertSee('Curtains &amp; Blinds', false);

        $this->actingAs($admin)->put(route('admin.services.update', $service), $this->payload([
            'name' => 'Curtains',
            'slug' => 'curtains',
            'is_featured' => '1',
            'sections' => [],
        ]))->assertRedirect(route('admin.services.edit', 'curtains'));

        $service->refresh();
        $this->assertSame('Curtains', $service->name);
        $this->assertTrue($service->is_featured);
        $this->assertCount(0, $service->sections);

        $this->actingAs($admin)->delete(route('admin.services.destroy', $service))->assertRedirect(route('admin.services.index'));
        $this->assertSoftDeleted($service);
    }

    public function test_service_validation_errors_are_shown_inline(): void
    {
        $this->actingAs($this->admin())
            ->from(route('admin.services.create'))
            ->post(route('admin.services.store'), ['name' => ''])
            ->assertSessionHasErrors(['name', 'short_description', 'hero_title', 'intro']);
    }
}
