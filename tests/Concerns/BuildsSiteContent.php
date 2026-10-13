<?php

namespace Tests\Concerns;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Media;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\Setting;

/**
 * Minimal, explicit fixtures for public-site tests (no image files needed).
 */
trait BuildsSiteContent
{
    protected function media(string $slug = 'test-image'): Media
    {
        return Media::create([
            'path' => "media/library/{$slug}", 'alt' => 'A test interior', 'category' => 'living-room',
            'width' => 1600, 'height' => 1000, 'variants' => [480, 960, 1600],
        ]);
    }

    protected function page(string $slug, array $sections = [], array $attributes = []): Page
    {
        $page = Page::create($attributes + ['slug' => $slug, 'title' => ucwords(str_replace('-', ' ', $slug)), 'meta_description' => "About {$slug}"]);
        foreach ($sections as $i => $section) {
            $page->sections()->create($section + ['sort_order' => $i]);
        }

        return $page;
    }

    protected function service(array $attributes = []): Service
    {
        return Service::create($attributes + [
            'name' => 'Living Room Decoration', 'slug' => 'living-room-decoration', 'icon' => 'sofa',
            'short_description' => 'Calm, layered living rooms.', 'hero_title' => 'Professional Living Room Decoration Services',
            'hero_description' => 'Design-led living rooms.', 'hero_media_id' => $this->media('svc-'.uniqid())->id,
            'intro' => '<p>Unique living room introduction.</p>',
            'included' => [['title' => 'Furniture layout', 'text' => 'Planned around how you live.']],
            'benefits' => [['title' => 'Cohesion', 'text' => 'Everything works together.']],
            'meta_title' => 'Living Room Decoration Services', 'is_published' => true, 'is_featured' => true,
        ]);
    }

    protected function project(array $attributes = []): Project
    {
        return Project::create($attributes + [
            'title' => 'Warm Minimal Living Room', 'slug' => 'warm-minimal-living-room',
            'project_category_id' => ProjectCategory::firstOrCreate(['slug' => 'living-rooms'], ['name' => 'Living Rooms'])->id,
            'summary' => 'A calm living room in warm neutrals.', 'overview' => '<p>Overview text.</p>',
            'cover_media_id' => $this->media('proj-'.uniqid())->id, 'is_published' => true,
        ]);
    }

    protected function blogPost(array $attributes = []): BlogPost
    {
        $post = BlogPost::create($attributes + [
            'title' => 'Layered Lighting Guide', 'slug' => 'layered-lighting-guide', 'excerpt' => 'How to light a room.',
            'body' => '<h2>Ambient</h2><p>a</p><h2>Task</h2><p>b</p><h2>Accent</h2><p>c</p>',
            'status' => 'published', 'published_at' => now()->subDay(),
        ]);
        $post->categories()->sync([BlogCategory::firstOrCreate(['slug' => 'lighting-ideas'], ['name' => 'Lighting Ideas'])->id]);

        return $post;
    }

    /** The pages and menus every public request needs. */
    protected function baseSite(): void
    {
        Setting::put(['site_name' => 'Test Decor', 'process_steps' => [['title' => 'Initial Consultation', 'text' => 'We listen.', 'icon' => 'message']]]);
        NavigationItem::create(['menu' => 'header', 'label' => 'Services', 'url' => '/services']);
        NavigationItem::create(['menu' => 'footer_legal', 'label' => 'Privacy Policy', 'url' => '/privacy-policy']);

        $hero = fn ($heading) => ['type' => 'hero', 'heading' => $heading, 'media_id' => $this->media('hero-'.uniqid())->id];
        $this->page('home', [
            ['type' => 'announcement', 'body' => 'Free consultations'],
            $hero('Beautiful Home Decor') + ['data' => ['size' => 'full', 'buttons' => [['label' => 'Explore', 'url' => '/services']]]],
            ['type' => 'stats', 'heading' => 'Numbers', 'data' => ['items' => []]],
            ['type' => 'services', 'heading' => 'Our Services', 'data' => ['limit' => 6]],
            ['type' => 'testimonials', 'heading' => 'Reviews'],
            ['type' => 'process', 'heading' => 'Our Process'],
            ['type' => 'blog', 'heading' => 'Latest Articles'],
            ['type' => 'cta', 'heading' => 'Ready?'],
        ]);
        $this->page('services', [$hero('All Services'), ['type' => 'services', 'heading' => 'Services']]);
        $this->page('gallery', [$hero('Portfolio'), ['type' => 'portfolio', 'heading' => 'Projects']]);
        $this->page('blog', [$hero('Journal')]);
        $this->page('contact', [$hero('Contact'), ['type' => 'contact', 'heading' => 'Get in touch']]);
        $this->page('book-consultation', [$hero('Book'), ['type' => 'consultation_form', 'heading' => 'Request']]);
        $this->page('privacy-policy', [], ['template' => 'legal', 'body' => '<h2>Data</h2><p>:site_name respects your privacy.</p>']);
    }
}
