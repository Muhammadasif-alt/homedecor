<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Media;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

class RemovedChimineaGuideTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();
    }

    public function test_migration_removes_the_live_chiminea_guide_and_its_photo(): void
    {
        $photo = $this->media('cast-chiminea-log-stacks');
        $service = $this->service();
        $post = $this->blogPost(['slug' => 'how-to-build-a-chiminea', 'title' => 'How to Build a Chiminea', 'featured_media_id' => $photo->id]);
        $service->articles()->attach($post->id, ['sort_order' => 1]);

        (require database_path('migrations/2026_10_26_175813_remove_the_chiminea_guide_and_photo.php'))->up();

        $this->assertSoftDeleted($post);
        $this->assertModelMissing($photo);
        $this->assertCount(0, $service->fresh()->articles);
        $this->get('/blog/how-to-build-a-chiminea')->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('how-to-build-a-chiminea');
    }

    public function test_migration_is_harmless_when_the_guide_never_existed(): void
    {
        $count = BlogPost::withTrashed()->count();

        (require database_path('migrations/2026_10_26_175813_remove_the_chiminea_guide_and_photo.php'))->up();

        $this->assertSame($count, BlogPost::withTrashed()->count());
    }

    public function test_guide_seeder_no_longer_ships_the_chiminea_guide(): void
    {
        $this->assertNotContains('how-to-build-a-chiminea', array_column(GuidePostSeeder::guides(), 'slug'));
        $this->assertFalse(Media::where('path', 'media/library/cast-chiminea-log-stacks')->exists());
    }
}
