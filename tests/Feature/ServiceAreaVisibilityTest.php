<?php

namespace Tests\Feature;

use App\Models\NavigationItem;
use App\Models\ServiceArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

class ServiceAreaVisibilityTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();
        $this->page('service-areas', [['type' => 'hero', 'heading' => 'Where We Work', 'media_id' => $this->media('areas-hero')->id]]);
        NavigationItem::create(['menu' => 'footer_quick', 'label' => 'Service Areas', 'url' => '/service-areas']);
    }

    private function area(array $attributes = []): ServiceArea
    {
        return ServiceArea::create($attributes + ['name' => 'Lahore', 'slug' => 'lahore', 'is_published' => true]);
    }

    public function test_empty_service_areas_are_hidden_from_sitemap_and_navigation(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertDontSee(url('/service-areas'));
        $this->get('/')->assertOk()->assertDontSee('href="'.url('/service-areas').'"', false);
    }

    public function test_empty_service_areas_page_still_loads_but_is_noindexed(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->get('/service-areas')->assertOk()->assertSee('content="noindex, nofollow"', false);
    }

    public function test_unpublished_areas_count_as_empty(): void
    {
        $this->area(['is_published' => false]);

        $this->get('/sitemap.xml')->assertOk()->assertDontSee(url('/service-areas'));
        $this->get('/')->assertOk()->assertDontSee('href="'.url('/service-areas').'"', false);
    }

    public function test_published_area_restores_sitemap_entry_navigation_and_indexing(): void
    {
        $this->area();

        $this->get('/sitemap.xml')->assertOk()->assertSee(url('/service-areas'));
        $this->get('/')->assertOk()->assertSee('href="'.url('/service-areas').'"', false);

        $this->app->detectEnvironment(fn () => 'production');
        $this->get('/service-areas')->assertOk()->assertSee('content="index, follow', false);
    }

    public function test_disabled_navigation_items_are_not_rendered(): void
    {
        NavigationItem::create(['menu' => 'footer_quick', 'label' => 'Testimonials', 'url' => '/testimonials', 'is_enabled' => false]);

        $this->get('/')->assertOk()->assertDontSee('href="'.url('/testimonials').'"', false);
    }
}
