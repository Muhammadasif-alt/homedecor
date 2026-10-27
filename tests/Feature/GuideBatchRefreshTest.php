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
}
