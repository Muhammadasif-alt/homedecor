<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

class GuideBatchRefreshTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();
    }

    public function test_bamboo_batch_replaces_indoor_photos_on_live_posts(): void
    {
        $indoor = $this->media('staircase-atrium-indoor-planting');
        foreach (['bamboo-fence-mistakes', 'bamboo-fence-cost', 'bamboo-fence-maintenance-by-season'] as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old body</p>', 'featured_media_id' => $indoor->id]);
        }

        (require database_path('migrations/2026_10_27_062650_refresh_bamboo_fence_guides_with_matching_photos.php'))->up();

        foreach (['bamboo-fence-mistakes', 'bamboo-fence-cost', 'bamboo-fence-maintenance-by-season'] as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertStringStartsWith('media/library/', $post->featuredMedia->path);
            $this->assertNotSame($indoor->id, $post->featured_media_id, $slug);
            $this->assertSame(3, substr_count($post->body, '<figure>'), $slug);
            $this->assertStringContainsString('id="faq"', $post->body);
            $this->assertStringContainsString('id="final-thoughts"', $post->body);
        }
        $this->assertSame(2, Media::whereIn('path', ['media/library/bamboo-fence-japanese-garden', 'media/library/new-golden-bamboo-fence'])->count());
    }

    public function test_sync_leaves_posts_it_was_not_asked_about(): void
    {
        $post = $this->blogPost(['slug' => 'layered-lighting-guide']);

        (require database_path('migrations/2026_10_27_062650_refresh_bamboo_fence_guides_with_matching_photos.php'))->up();

        $this->assertSame('Layered Lighting Guide', $post->fresh()->title);
    }

    public function test_aluminium_and_wood_batch_gives_each_guide_a_fence_photo_and_three_section_photos(): void
    {
        $old = $this->media('proposal-cost-estimate-laptop');
        $slugs = ['aluminium-fence-cost', 'aluminium-fence-maintenance-by-season', 'aluminium-fence-mistakes', 'how-to-build-a-wood-fence', 'wood-fence-pros-and-cons'];
        foreach ($slugs as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old</p>', 'featured_media_id' => $old->id]);
        }

        (require database_path('migrations/2026_10_27_063927_refresh_aluminium_and_wood_fence_guides_with_matching_photos.php'))->up();

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertNotSame($old->id, $post->featured_media_id, $slug);
            $this->assertSame('outdoor', $post->featuredMedia->category, $slug);
            $this->assertSame(3, substr_count($post->body, '<figure>'), $slug);
            $this->assertMatchesRegularExpression('/<h2 id="[a-z-]+">1\. /', $post->body, $slug);
        }
    }

    public function test_privacy_and_vinyl_batch_gives_each_guide_a_fence_photo_and_section_photos(): void
    {
        $old = $this->media('garden-lawn-pergola-dusk');
        $this->media('balcony-lounge-string-lights');
        $slugs = ['privacy-fence-cost', 'privacy-fence-mistakes', 'privacy-fence-small-yard-ideas', 'vinyl-fence-pros-and-cons', 'vinyl-fence-small-yard-ideas'];
        foreach ($slugs as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old</p>', 'featured_media_id' => $old->id]);
        }

        (require database_path('migrations/2026_10_27_065538_refresh_privacy_and_vinyl_fence_guides_with_matching_photos.php'))->up();

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertNotSame($old->id, $post->featured_media_id, $slug);
            $this->assertGreaterThanOrEqual(3, substr_count($post->body, '<figure>'), $slug);
            $this->assertStringNotContainsString('garden-lawn-pergola-dusk', $post->body, $slug);
        }
    }
}
