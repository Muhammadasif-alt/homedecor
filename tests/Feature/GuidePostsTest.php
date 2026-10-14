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

class GuidePostsTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    /** slug => [service slug, inline photo count] */
    private const GUIDES = [
        'living-room-colour-scheme-guide' => ['living-room-decoration', 4],
        'small-living-room-decorating-ideas' => ['small-space-apartment-decoration', 7],
        'living-room-furniture-arrangement-guide' => ['furniture-styling', 4],
        'living-room-feature-wall-ideas' => ['wall-decor-wallpaper', 6],
        'living-room-rugs-and-curtains-guide' => ['curtains-window-treatments', 4],
        'living-room-lighting-ideas' => ['lighting-ceiling-decor', 5],
    ];

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
        foreach (self::GUIDES as $slug => [, $figures]) {
            $post = BlogPost::where('slug', $slug)->with('categories')->firstOrFail();

            $this->assertNotEmpty($post->categories, $slug);
            $this->assertLessThanOrEqual(160, mb_strlen($post->meta_description), $slug);
            $this->assertStringNotContainsString('<!--figure', $post->body);
            $this->assertSame($figures, substr_count($post->body, '<figure>'), $slug);

            $html = $this->get("/blog/{$slug}")->assertOk()->assertSee($post->title)
                ->assertSee('src="/media/library/', false)->getContent();
            $this->assertSame(1, substr_count($html, '<h1'), $slug);

            preg_match_all('/href="(\/(?:blog|services)\/[a-z0-9-]+)"/', $post->body, $links);
            $this->assertNotEmpty($links[1], $slug);
            foreach (array_unique($links[1]) as $path) {
                $this->get($path)->assertOk();
            }
        }

        $this->assertSame(count(self::GUIDES), BlogPost::whereIn('slug', array_keys(self::GUIDES))->distinct()->count('featured_media_id'));
    }

    public function test_each_service_leads_with_its_guide(): void
    {
        foreach (self::GUIDES as $slug => [$service]) {
            $this->assertSame($slug, Service::where('slug', $service)->firstOrFail()->articles->first()->slug);
        }
    }

    public function test_seeder_never_overwrites_admin_edits(): void
    {
        BlogPost::where('slug', 'small-living-room-decorating-ideas')->update(['title' => 'Edited in admin']);

        $this->seed(GuidePostSeeder::class);

        $this->assertSame('Edited in admin', BlogPost::where('slug', 'small-living-room-decorating-ideas')->value('title'));
        $this->assertSame(30 + count(self::GUIDES), BlogPost::count());
    }
}
