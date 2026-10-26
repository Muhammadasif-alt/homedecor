<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Service;
use Database\Seeders\BlogCategorySeeder;
use Database\Seeders\BlogPostSeeder;
use Database\Seeders\ExtendedBlogPostSeeder;
use Database\Seeders\ExtendedServiceSeeder;
use Database\Seeders\GuidePostSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

/** Checks every guide file in database/data/guides. */
class GuidePostsTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();

        foreach (require database_path('data/image-manifest.php') as [, $slug]) {
            $this->media($slug);
        }
        $this->seed([
            ServiceSeeder::class, BlogCategorySeeder::class, BlogPostSeeder::class,
            ExtendedServiceSeeder::class, ExtendedBlogPostSeeder::class, GuidePostSeeder::class,
        ]);
    }

    public function test_guides_render_with_library_photos_and_working_links(): void
    {
        $guides = GuidePostSeeder::guides();

        foreach ($guides as $guide) {
            $slug = $guide['slug'];
            $post = BlogPost::where('slug', $slug)->with('categories')->firstOrFail();

            $this->assertNotEmpty($post->categories, $slug);
            $this->assertLessThanOrEqual(160, mb_strlen($post->meta_description), $slug);
            $this->assertStringNotContainsString('<h1', $post->body, $slug);
            $this->assertStringNotContainsString('<!--figure', $post->body, $slug);
            $this->assertSame(substr_count($guide['body'], '<!--figure:'), substr_count($post->body, '<figure>'), $slug);

            $html = $this->get("/blog/{$slug}")->assertOk()->assertSee($post->title)->getContent();
            $this->assertSame(1, substr_count($html, '<h1'), $slug);

            preg_match_all('/href="(\/(?:blog|services)\/[a-z0-9-]+)"/', $post->body, $links);
            $this->assertNotEmpty($links[1], $slug);
            foreach (array_unique($links[1]) as $path) {
                $this->get($path)->assertOk();
            }
        }

        // The library has run out of unused outdoor photos, so a featured photo may be shared by at most two articles.
        $this->assertLessThanOrEqual(2, BlogPost::selectRaw('count(*) as uses')->groupBy('featured_media_id')->get()->max('uses'));
        $this->assertSame(count($guides), count(array_unique(array_column($guides, 'meta_title'))));
    }

    public function test_each_guide_is_listed_on_its_service(): void
    {
        foreach (GuidePostSeeder::guides() as $guide) {
            $articles = Service::where('slug', $guide['service'])->firstOrFail()->articles->pluck('slug');
            $this->assertContains($guide['slug'], $articles, $guide['slug']);
        }
    }

    public function test_seeder_never_overwrites_admin_edits(): void
    {
        BlogPost::where('slug', 'small-living-room-decorating-ideas')->update(['title' => 'Edited in admin']);
        $count = BlogPost::count();

        $this->seed(GuidePostSeeder::class);

        $this->assertSame('Edited in admin', BlogPost::where('slug', 'small-living-room-decorating-ideas')->value('title'));
        $this->assertSame($count, BlogPost::count());
    }
}
