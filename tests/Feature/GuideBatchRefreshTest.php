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

    public function test_chain_link_split_rail_and_slat_batch_replaces_construction_photos(): void
    {
        $old = $this->media('house-scaffolding-construction');
        $slugs = ['chain-link-fence-pros-and-cons', 'chain-link-fence-small-yard-ideas', 'how-to-build-a-chain-link-fence', 'split-rail-fence-mistakes', 'horizontal-slat-fence-pros-and-cons', 'horizontal-slat-fence-small-yard-ideas'];
        foreach ($slugs as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old</p>', 'featured_media_id' => $old->id]);
        }

        (require database_path('migrations/2026_10_27_071824_refresh_chain_link_split_rail_and_slat_fence_guides.php'))->up();

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertNotSame($old->id, $post->featured_media_id, $slug);
            $this->assertGreaterThanOrEqual(2, substr_count($post->body, '<figure>'), $slug);
            $this->assertMatchesRegularExpression('/<h2 id="[a-z-]+">1\. /', $post->body, $slug);
        }
    }

    public function test_deck_batch_replaces_office_and_villa_photos(): void
    {
        $old = $this->media('proposal-cost-estimate-laptop');
        $slugs = ['composite-deck-cost', 'composite-deck-maintenance-by-season', 'composite-deck-mistakes', 'wood-deck-cost', 'wood-deck-pros-and-cons', 'small-yard-wood-deck-ideas'];
        foreach ($slugs as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old</p>', 'featured_media_id' => $old->id]);
        }

        (require database_path('migrations/2026_10_27_073345_refresh_composite_and_wood_deck_guides_with_matching_photos.php'))->up();

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertNotSame($old->id, $post->featured_media_id, $slug);
            $this->assertGreaterThanOrEqual(2, substr_count($post->body, '<figure>'), $slug);
            $this->assertDoesNotMatchRegularExpression('/landscape-|site-plan|balcony-lounge|garden-lawn/', $post->body, $slug);
        }
        $this->assertStringContainsString('best kept off a timber deck', BlogPost::where('slug', 'small-yard-wood-deck-ideas')->value('body'));
    }

    public function test_floating_deck_and_railing_batch_replaces_off_topic_photos(): void
    {
        $old = $this->media('design-studio-drawings');
        $slugs = ['floating-deck-pros-and-cons', 'how-to-build-a-floating-deck', 'floating-deck-ideas-small-yards', 'deck-railing-cost', 'deck-railing-maintenance-by-season', 'deck-railing-mistakes'];
        foreach ($slugs as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old</p>', 'featured_media_id' => $old->id]);
        }

        (require database_path('migrations/2026_10_27_080555_refresh_floating_deck_and_railing_guides_with_matching_photos.php'))->up();

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertNotSame($old->id, $post->featured_media_id, $slug);
            $this->assertGreaterThanOrEqual(2, substr_count($post->body, '<figure>'), $slug);
            $this->assertDoesNotMatchRegularExpression('/landscape-plan|balcony-lounge|garden-lawn|garden-stone-path/', $post->body, $slug);
        }
        $this->assertStringContainsString('best kept off the deck', BlogPost::where('slug', 'floating-deck-ideas-small-yards')->value('body'));
    }

    public function test_deck_skirting_and_patio_combo_batch_replaces_off_topic_photos(): void
    {
        $old = $this->media('master-plan-on-site');
        $slugs = ['deck-skirting-cost', 'deck-skirting-ideas-small-yards', 'deck-skirting-mistakes', 'how-to-build-a-patio-deck-combo', 'patio-deck-combo-pros-and-cons', 'patio-deck-combo-small-yards'];
        foreach ($slugs as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old</p>', 'featured_media_id' => $old->id]);
        }

        (require database_path('migrations/2026_10_27_084326_refresh_deck_skirting_and_patio_deck_combo_guides.php'))->up();

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertNotSame($old->id, $post->featured_media_id, $slug);
            $this->assertGreaterThanOrEqual(1, substr_count($post->body, '<figure>'), $slug);
            $this->assertDoesNotMatchRegularExpression('/landscape-plan|villa-garden|garden-lawn|garden-stone-path|garden-water/', $post->body, $slug);
        }
        $this->assertStringContainsString('">1. The pros', BlogPost::where('slug', 'patio-deck-combo-pros-and-cons')->value('body'));
    }

    public function test_edging_batch_replaces_plan_and_construction_photos(): void
    {
        $old = $this->media('floor-plan-drawings-desk');
        $slugs = ['how-to-build-landscape-edging', 'landscape-edging-pros-and-cons', 'how-to-build-paver-edging', 'paver-edging-pros-and-cons', 'brick-edging-pros-and-cons', 'how-to-build-brick-edging'];
        foreach ($slugs as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old</p>', 'featured_media_id' => $old->id]);
        }

        (require database_path('migrations/2026_10_27_095540_refresh_landscape_paver_and_brick_edging_guides.php'))->up();

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertNotSame($old->id, $post->featured_media_id, $slug);
            $this->assertGreaterThanOrEqual(1, substr_count($post->body, '<figure>'), $slug);
            $this->assertDoesNotMatchRegularExpression('/garden-lawn|garden-stone-path/', $post->body, $slug);
        }
        $this->assertStringContainsString('">1. The pros of brick edging', BlogPost::where('slug', 'brick-edging-pros-and-cons')->value('body'));
    }

    public function test_stone_and_steel_edging_batch_replaces_villa_and_cost_photos(): void
    {
        $old = $this->media('cost-estimate-plans-review');
        $slugs = ['how-to-build-stone-edging', 'stone-edging-maintenance-by-season', 'steel-edging-cost', 'steel-edging-maintenance-by-season', 'steel-edging-mistakes'];
        foreach ($slugs as $slug) {
            $this->blogPost(['slug' => $slug, 'title' => 'Old', 'body' => '<p>Old</p>', 'featured_media_id' => $old->id]);
        }

        (require database_path('migrations/2026_10_27_121153_refresh_stone_and_steel_edging_guides_with_matching_photos.php'))->up();

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->with('featuredMedia')->firstOrFail();
            $this->assertNotSame($old->id, $post->featured_media_id, $slug);
            $this->assertGreaterThanOrEqual(1, substr_count($post->body, '<figure>'), $slug);
            $this->assertStringNotContainsString('garden-stone-path', $post->body, $slug);
        }
    }
}
