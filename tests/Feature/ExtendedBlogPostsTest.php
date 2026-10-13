<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Service;
use Database\Seeders\BlogCategorySeeder;
use Database\Seeders\BlogPostSeeder;
use Database\Seeders\ExtendedBlogPostSeeder;
use Database\Seeders\ExtendedServiceSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

/**
 * Seeds the full service and journal content and checks the twenty articles written for the extended services.
 */
class ExtendedBlogPostsTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    private const SERVICE_SLUGS = [
        'dining-room-decoration', 'home-office-decoration', 'kids-room-nursery-decoration',
        'balcony-terrace-decoration', 'entryway-hallway-decoration', 'walk-in-closet-dressing-room-styling',
        'small-space-apartment-decoration', 'airbnb-vacation-rental-styling', 'home-staging-property-sale',
        'seasonal-holiday-home-decoration',
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
            ExtendedServiceSeeder::class, ExtendedBlogPostSeeder::class,
        ]);
    }

    private function newPosts()
    {
        return BlogPost::whereIn('service_id', Service::whereIn('slug', self::SERVICE_SLUGS)->pluck('id'))
            ->with(['service', 'categories'])->get();
    }

    public function test_two_published_articles_per_extended_service(): void
    {
        $posts = $this->newPosts();

        $this->assertCount(20, $posts);
        $this->assertSame(30, BlogPost::count());
        foreach (self::SERVICE_SLUGS as $slug) {
            $this->assertSame(2, $posts->where('service.slug', $slug)->count(), $slug);
        }
        $this->assertSame(20, $posts->pluck('meta_title')->unique()->count());
        $this->assertSame(20, $posts->pluck('meta_description')->unique()->count());
        $this->assertSame(20, $posts->pluck('featured_media_id')->unique()->count(), 'each article has its own image');
        $posts->each(fn ($p) => $this->assertLessThanOrEqual(160, mb_strlen($p->meta_description), $p->slug));
        $posts->each(fn ($p) => $this->assertNotEmpty($p->categories, $p->slug));
    }

    public function test_every_article_renders_and_every_internal_link_resolves(): void
    {
        foreach ($this->newPosts() as $post) {
            $html = $this->get("/blog/{$post->slug}")->assertOk()->assertSee($post->title)->getContent();
            $this->assertSame(1, substr_count($html, '<h1'), "{$post->slug} must have exactly one h1");

            preg_match_all('/href="(\/(?:blog|services)\/[a-z0-9-]+)"/', $post->body, $links);
            $this->assertNotEmpty($links[1], "{$post->slug} links nowhere");
            foreach (array_unique($links[1]) as $path) {
                $this->get($path)->assertOk();
            }
        }
    }

    public function test_service_pages_lead_with_their_own_articles(): void
    {
        $service = Service::where('slug', 'dining-room-decoration')->firstOrFail();

        $this->assertSame(
            ['how-to-choose-a-dining-table-and-chairs', 'dining-room-lighting-chandelier-size-and-height'],
            $service->articles->take(2)->pluck('slug')->all()
        );
        $this->get('/services/dining-room-decoration')->assertOk()
            ->assertSee('How to Choose the Right Dining Table and Chairs');
    }

    public function test_new_categories_and_sitemap(): void
    {
        $this->get('/blog/category/staging-rentals')->assertOk()->assertSee('Home Staging Tips to Help Your Property Sell');
        $sitemap = $this->get('/sitemap.xml')->assertOk()->getContent();
        foreach ($this->newPosts() as $post) {
            $this->assertStringContainsString(route('blog.show', $post), $sitemap);
        }
    }

    public function test_seeder_never_overwrites_admin_edits(): void
    {
        BlogPost::where('slug', 'small-balcony-decorating-ideas')->update(['title' => 'Edited in admin']);

        $this->seed(ExtendedBlogPostSeeder::class);

        $this->assertSame('Edited in admin', BlogPost::where('slug', 'small-balcony-decorating-ideas')->value('title'));
        $this->assertSame(30, BlogPost::count());
    }
}
