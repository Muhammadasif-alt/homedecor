<?php

namespace Tests\Feature;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();
    }

    public function test_home_page_renders_cms_sections_with_a_single_h1(): void
    {
        $this->service();
        $this->blogPost();

        $html = $this->get('/')->assertOk()
            ->assertSee('Beautiful Home Decor')
            ->assertSee('Living Room Decoration')
            ->assertSee('Layered Lighting Guide')
            ->assertSee('Initial Consultation')
            ->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString('application/ld+json', $html);
    }

    public function test_empty_stats_and_testimonials_sections_are_not_rendered(): void
    {
        $this->get('/')->assertOk()->assertDontSee('Numbers')->assertDontSee('Reviews');
    }

    public function test_before_after_section_renders_pairs_from_section_items(): void
    {
        $before = $this->media('site-before');
        $after = $this->media('site-after');
        $this->page('transformations', [
            ['type' => 'before_after', 'heading' => 'Before & after', 'data' => ['items' => [
                ['title' => 'Villa exterior', 'before_media_id' => $before->id, 'after_media_id' => $after->id],
                ['title' => 'Broken pair', 'before_media_id' => $before->id, 'after_media_id' => 999],
            ]]],
        ]);

        $this->get('/transformations')->assertOk()
            ->assertSee('Villa exterior')->assertSee('Compare before and after: Villa exterior', false)
            ->assertDontSee('Broken pair');
    }

    public function test_every_service_uses_the_shared_template(): void
    {
        $this->service();
        $this->service(['name' => 'Bedroom Decoration', 'slug' => 'bedroom-decoration', 'hero_title' => 'Bedroom Decoration Services', 'intro' => '<p>Restful bedroom copy.</p>']);

        $this->get('/services/living-room-decoration')->assertOk()
            ->assertSee('Professional Living Room Decoration Services')
            ->assertSee('Unique living room introduction.')
            ->assertSee('"@type":"Service"', false)
            ->assertSee('BreadcrumbList');

        $this->get('/services/bedroom-decoration')->assertOk()->assertSee('Restful bedroom copy.');
    }

    public function test_unpublished_service_returns_404(): void
    {
        $this->service(['is_published' => false]);

        $this->get('/services/living-room-decoration')->assertNotFound();
    }

    public function test_project_detail_and_portfolio_render(): void
    {
        $this->project();

        $this->get('/gallery')->assertOk()->assertSee('Warm Minimal Living Room')->assertSee('Living Rooms');
        $this->get('/gallery/warm-minimal-living-room')->assertOk()->assertSee('Overview text.');
    }

    public function test_blog_listing_detail_category_and_search(): void
    {
        $this->blogPost();

        $this->get('/blog')->assertOk()->assertSee('Layered Lighting Guide');
        $this->get('/blog/category/lighting-ideas')->assertOk()->assertSee('Layered Lighting Guide');
        $this->get('/blog?q=lighting')->assertOk()->assertSee('1 result')->assertSee('noindex', false);
        $this->get('/blog/layered-lighting-guide')->assertOk()
            ->assertSee('In this article')
            ->assertSee('id="ambient"', false)
            ->assertSee('BlogPosting');
    }

    public function test_scheduled_and_draft_posts_are_hidden(): void
    {
        $this->blogPost(['slug' => 'future', 'published_at' => now()->addWeek()]);
        $this->blogPost(['slug' => 'draft', 'title' => 'Draft post', 'status' => 'draft']);

        $this->get('/blog/future')->assertNotFound();
        $this->get('/blog/draft')->assertNotFound();
        $this->get('/blog')->assertDontSee('Draft post');
    }

    public function test_legal_page_replaces_business_tokens(): void
    {
        $this->get('/privacy-policy')->assertOk()->assertSee('Test Decor respects your privacy.');
    }

    public function test_unknown_and_reserved_slugs_return_404(): void
    {
        $this->get('/does-not-exist')->assertNotFound()->assertSee('This room seems to be empty');
        $this->get('/home')->assertNotFound();
    }

    public function test_sitemap_lists_published_content_and_robots_blocks_non_production(): void
    {
        $this->service();
        $this->project();
        $this->blogPost();

        $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(url('/services/living-room-decoration'))
            ->assertSee(url('/gallery/warm-minimal-living-room'))
            ->assertSee(url('/blog/layered-lighting-guide'));

        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /');
    }

    public function test_admin_managed_redirects_are_applied(): void
    {
        Redirect::create(['from_path' => '/old-services', 'to_url' => '/services', 'status_code' => 301]);

        $this->get('/old-services/')->assertRedirect('/services')->assertStatus(301);
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get('/')->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }
}
