<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Service;
use Database\Seeders\BlogCategorySeeder;
use Database\Seeders\BlogPostSeeder;
use Database\Seeders\LivingRoomColourPostSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

class LivingRoomColourPostTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    private const SLUG = 'living-room-colour-scheme-guide';

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();

        foreach (require database_path('data/image-manifest.php') as [, $slug]) {
            $this->media($slug);
        }
        $this->seed([ServiceSeeder::class, BlogCategorySeeder::class, BlogPostSeeder::class, LivingRoomColourPostSeeder::class]);
    }

    public function test_article_renders_with_library_photos_and_working_links(): void
    {
        $post = BlogPost::where('slug', self::SLUG)->with('categories')->firstOrFail();

        $this->assertSame(['living-room-decor', 'wall-decor'], $post->categories->pluck('slug')->sort()->values()->all());
        $this->assertLessThanOrEqual(160, mb_strlen($post->meta_description));
        $this->assertStringNotContainsString('<!--figure', $post->body);
        $this->assertSame(4, substr_count($post->body, '<figure>'));

        $html = $this->get('/blog/'.self::SLUG)->assertOk()
            ->assertSee('The Ultimate Guide to Choosing a Living Room Colour Scheme')
            ->assertSee('src="/media/library/minimal-beige-living-room-', false)
            ->getContent();
        $this->assertSame(1, substr_count($html, '<h1'));

        preg_match_all('/href="(\/(?:blog|services)\/[a-z0-9-]+)"/', $post->body, $links);
        $this->assertCount(4, array_unique($links[1]));
        foreach (array_unique($links[1]) as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_living_room_service_leads_with_the_guide(): void
    {
        $service = Service::where('slug', 'living-room-decoration')->firstOrFail();

        $this->assertSame(self::SLUG, $service->articles->first()->slug);
    }

    public function test_seeder_never_overwrites_admin_edits(): void
    {
        BlogPost::where('slug', self::SLUG)->update(['title' => 'Edited in admin']);

        $this->seed(LivingRoomColourPostSeeder::class);

        $this->assertSame('Edited in admin', BlogPost::where('slug', self::SLUG)->value('title'));
        $this->assertSame(1, BlogPost::where('slug', self::SLUG)->count());
    }
}
