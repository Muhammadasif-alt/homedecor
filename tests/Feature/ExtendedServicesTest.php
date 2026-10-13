<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Database\Seeders\ExtendedServiceSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

/**
 * Seeds the real 10 core + 10 extended services (with placeholder media rows for every library slug)
 * and checks that all 20 render through the shared template and appear everywhere they should.
 */
class ExtendedServicesTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    private const NEW_SLUGS = [
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
        $this->seed([ServiceSeeder::class, ExtendedServiceSeeder::class]);
    }

    public function test_twenty_published_services_in_order(): void
    {
        $services = Service::published()->ordered()->get();

        $this->assertCount(20, $services);
        $this->assertSame(self::NEW_SLUGS, $services->slice(10)->pluck('slug')->values()->all());
        $this->assertSame(range(1, 20), $services->pluck('sort_order')->all());
        $this->assertSame(20, $services->pluck('meta_title')->unique()->count());
        $this->assertSame(20, $services->pluck('meta_description')->unique()->count());
    }

    public function test_style_cards_always_fill_complete_rows(): void
    {
        // The grid is 4 across for four styles and 3 across otherwise, so no row may be left half empty.
        foreach (Service::all() as $service) {
            $count = count($service->styles);
            $this->assertTrue($count === 4 || $count % 3 === 0, "{$service->slug} has {$count} styles");
            // "What's included" is always 3 across.
            $this->assertSame(0, count($service->included) % 3, "{$service->slug} has ".count($service->included).' included items');
        }
    }

    public function test_every_new_service_page_renders_with_unique_seo_and_schema(): void
    {
        $titles = [];

        foreach (self::NEW_SLUGS as $slug) {
            $service = Service::where('slug', $slug)->firstOrFail();
            $html = $this->get("/services/{$slug}")->assertOk()
                ->assertSee($service->hero_title)
                ->assertSee('"@type":"Service"', false)
                ->assertSee('"@type":"FAQPage"', false)
                ->assertSee('Related home decor services')
                ->assertSee('Book a consultation')
                ->getContent();

            $this->assertSame(1, substr_count($html, '<h1'), "{$slug} must have exactly one h1");
            $this->assertMatchesRegularExpression('/<link rel="canonical" href="[^"]*\/services\/'.$slug.'"/', $html);
            preg_match('/<title>(.*?)<\/title>/s', $html, $title);
            $titles[] = $title[1];
            $this->assertLessThanOrEqual(160, strlen($service->meta_description), "{$slug} meta description");
        }

        $this->assertCount(10, array_unique($titles));
    }

    public function test_genuine_projects_and_articles_are_linked_only_where_relevant(): void
    {
        $this->project(['title' => 'Statement-Lit Dining Rooms', 'slug' => 'statement-lit-dining-rooms']);
        $post = $this->blogPost(['title' => 'Seasonal Decor Through the Year', 'slug' => 'seasonal-decor-ideas-through-the-year']);
        Service::whereIn('slug', ['dining-room-decoration', 'seasonal-holiday-home-decoration'])->forceDelete();
        $this->seed(ExtendedServiceSeeder::class);

        $this->get('/services/dining-room-decoration')->assertOk()->assertSee('Statement-Lit Dining Rooms');
        $this->get('/services/seasonal-holiday-home-decoration')->assertOk()
            ->assertSee('Further reading')->assertSee($post->title);
        // No portfolio is invented for services without genuine projects.
        $this->get('/services/home-staging-property-sale')->assertOk()->assertDontSee('home-staging-property-sale projects');
        $this->assertSame(0, Service::where('slug', 'home-staging-property-sale')->first()->projects()->count());
    }

    public function test_all_twenty_services_appear_in_menu_footer_overview_and_sitemap(): void
    {
        $home = $this->get('/')->assertOk()->getContent();
        $overview = $this->get('/services')->assertOk()->getContent();
        $sitemap = $this->get('/sitemap.xml')->assertOk()->getContent();

        // The footer lists the first 8 and links to the full overview.
        $this->assertStringContainsString('View all 20 services', $home);

        foreach (Service::all() as $service) {
            $url = route('services.show', $service);
            // Header dropdown on every page.
            $this->assertStringContainsString('href="'.$url.'"', $home, "{$service->slug} in header dropdown");
            $this->assertStringContainsString('href="'.$url.'"', $overview);
            $this->assertStringContainsString('<loc>'.$url.'</loc>', $sitemap);
        }
    }

    public function test_draft_services_stay_hidden_everywhere(): void
    {
        $draft = Service::where('slug', 'home-staging-property-sale')->firstOrFail();
        $draft->update(['is_published' => false]);
        cache()->flush();

        $this->get('/services/home-staging-property-sale')->assertNotFound();
        $this->get('/sitemap.xml')->assertDontSee('home-staging-property-sale');
        $this->get('/services')->assertDontSee('/services/home-staging-property-sale');
    }

    public function test_seeder_never_overwrites_admin_edits(): void
    {
        Service::where('slug', 'dining-room-decoration')->update(['hero_title' => 'Edited in admin', 'is_published' => false]);

        $this->seed(ExtendedServiceSeeder::class);

        $service = Service::where('slug', 'dining-room-decoration')->first();
        $this->assertSame('Edited in admin', $service->hero_title);
        $this->assertFalse($service->is_published);
        $this->assertSame(3, $service->faqs()->count(), 'FAQs are not duplicated');
    }

    public function test_admin_can_edit_a_new_service_and_its_related_articles(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $service = Service::where('slug', 'home-office-decoration')->firstOrFail();
        $post = $this->blogPost();

        $this->actingAs($admin)->get(route('admin.services.edit', $service))->assertOk()
            ->assertSee('Home Office Decoration')->assertSee('Related articles');

        $this->actingAs($admin)->put(route('admin.services.update', $service), [
            'name' => $service->name, 'slug' => $service->slug, 'short_description' => $service->short_description,
            'hero_title' => 'Calm, focused home offices', 'hero_description' => $service->hero_description,
            'intro' => $service->intro, 'is_published' => '1', 'sort_order' => '12', 'articles' => [$post->id],
        ])->assertSessionHasNoErrors();

        $this->assertSame([$post->id], $service->fresh()->articles->pluck('id')->all());
        $this->get('/services/home-office-decoration')->assertOk()
            ->assertSee('Calm, focused home offices')->assertSee('Further reading')->assertSee($post->title);
    }
}
