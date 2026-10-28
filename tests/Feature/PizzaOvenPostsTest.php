<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

class PizzaOvenPostsTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    private const BUILD_BODY = "<p>This guide will walk you through the essential stages of creating your own backyard masterpiece.</p>\n"
        ."<h2 id=\"foundation\">1. Foundation</h2>\n\n<p>Slab.</p>\n<h2 id=\"stand\">2. Stand</h2>\n\n<p>Blocks.</p>\n"
        ."<h2 id=\"hearth\">3. Hearth</h2>\n\n<p>Firebricks.</p>\n<h2 id=\"dome\">4. Dome</h2>\n\n<p>Dome.</p>\n"
        ."<h2 id=\"insulation\">5. Insulation</h2>\n\n<p>Blanket.</p>\n<h2 id=\"chimney\">6. Chimney</h2>\n\n<p>Flue.</p>\n"
        ."<h2 id=\"render\">7. Render</h2>\n\n<p>Stucco.</p>\n<h2 id=\"curing\">8. Curing</h2>\n\n<p>Small fires.</p>\n"
        ."<h2 id=\"firing\">9. Firing</h2>\n\n<p>Cook.</p>\n<h2 id=\"faq\">FAQ</h2>\n<h3>Q?</h3>\n<p>A.</p>";

    private const PROS_BODY = "<p>Intro.</p>\n<h2 id=\"wood-fired\">Wood-Fired</h2>\n\n<p>Wood.</p>\n<h2 id=\"gas-powered\">Gas-Powered</h2>\n\n<p>Gas.</p>\n"
        ."<h2 id=\"heat-intensity\">Heat Intensity</h2>\n\n<p>Heat.</p>\n<h2 id=\"social-hub\">Social Hub</h2>\n\n<p>Party.</p>\n"
        ."<h2 id=\"learning-curve\">Learning Curve</h2>\n\n<p>Practice.</p>\n<h2 id=\"maintenance\">Maintenance</h2>\n\n<p>Rain and snow, which can degrade the insulation.</p>\n"
        ."<h2 id=\"portability\">Portability</h2>\n\n<p>Brands like Ooni, Gozney, and Roccbox have revolutionized the industry.</p>\n"
        ."<h2 id=\"cost\">Cost</h2>\n\n<p>Add another \$200 to \$500 to your initial budget.</p>\n"
        ."<h2 id=\"who-its-right-for\">Who It’s Right For</h2>\n<p><strong>The Frequent Host:</strong> Yes.</p>\n<h2 id=\"faq\">FAQ</h2>\n<h3>Q?</h3>\n<p>A.</p>";

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();
    }

    private function runMigration(): void
    {
        (require database_path('migrations/2026_10_28_075254_add_photos_and_seo_to_pizza_oven_posts.php'))->up();
    }

    public function test_both_posts_get_matching_photos_seo_fields_and_new_slugs(): void
    {
        $patio = $this->media('stone-patio-loungers-lawn');
        $category = BlogCategory::create(['name' => 'Outdoor Living', 'slug' => 'outdoor-living']);
        $service = $this->service(['name' => 'Balcony & Terrace Decoration', 'slug' => 'balcony-terrace-decoration']);
        $build = $this->blogPost(['slug' => 'how-to-build-an-outdoor-pizza-oven-step-by-step-for-diyers', 'title' => 'How to Build an Outdoor Pizza Oven: Step-by-Step for DIYers',
            'body' => self::BUILD_BODY, 'featured_media_id' => $patio->id, 'og_media_id' => $patio->id, 'meta_description' => 'Cut off…']);
        $pros = $this->blogPost(['slug' => 'outdoor-pizza-oven-pros-cons-and-who-its-right-for', 'title' => "Outdoor Pizza Oven: Pros, Cons, and Who It's Right for",
            'body' => self::PROS_BODY, 'featured_media_id' => $patio->id]);

        $this->runMigration();
        $build->refresh();
        $pros->refresh();

        $this->assertSame('how-to-build-an-outdoor-pizza-oven', $build->slug);
        $this->assertSame('outdoor-pizza-oven-pros-and-cons', $pros->slug);
        $this->assertSame('media/library/white-dome-pizza-oven-garden', $build->featuredMedia->path);
        $this->assertSame('media/library/pizza-baking-beside-wood-flames', $pros->featuredMedia->path);
        $this->assertNull($build->og_media_id);
        $this->assertSame(7, substr_count($build->body, '<figure>'));
        $this->assertSame(7, substr_count($pros->body, '<figure>'));
        $this->assertStringContainsString("<h2 id=\"stand\">2. Stand</h2>\n<figure><img src=\"/media/library/laying-concrete-blocks-with-mortar-1600.jpg\"", $build->body);
        $this->assertStringContainsString('href="/blog/outdoor-pizza-oven-pros-and-cons"', $build->body);
        $this->assertStringContainsString('href="/blog/how-to-build-an-outdoor-pizza-oven"', $pros->body);
        $this->assertStringContainsString('href="/blog/grill-island-maintenance-by-season"', $pros->body);
        $this->assertStringContainsString('Ooni and Gozney (maker of the Roccbox)', $pros->body);
        $this->assertStringNotContainsString('…', $build->meta_description);
        $this->assertLessThanOrEqual(160, mb_strlen($build->meta_description));
        $this->assertLessThanOrEqual(160, mb_strlen($pros->meta_description));
        $this->assertContains($category->id, $build->categories()->pluck('blog_categories.id')->all());
        $this->assertSame($service->id, $pros->service_id);

        $this->get('/blog/how-to-build-an-outdoor-pizza-oven-step-by-step-for-diyers')->assertRedirect('/blog/how-to-build-an-outdoor-pizza-oven');
        $this->get('/blog/outdoor-pizza-oven-pros-cons-and-who-its-right-for')->assertRedirect('/blog/outdoor-pizza-oven-pros-and-cons');
        $this->get(route('blog.show', $build))->assertOk()
            ->assertSee('How to Build an Outdoor Pizza Oven: 9 DIY Steps | Home Decor Studio', false)
            ->assertSee('/media/library/brick-dome-oven-wood-fire-1600.jpg', false);
    }

    public function test_running_twice_adds_no_extra_photos_or_redirects(): void
    {
        $this->blogPost(['slug' => 'outdoor-pizza-oven-pros-cons-and-who-its-right-for', 'title' => 'Pros', 'body' => self::PROS_BODY]);

        $this->runMigration();
        $this->runMigration();

        $post = BlogPost::where('slug', 'outdoor-pizza-oven-pros-and-cons')->firstOrFail();
        $this->assertSame(7, substr_count($post->body, '<figure>'));
        $this->assertSame(1, substr_count($post->body, 'step-by-step pizza oven build guide'));
        $this->assertSame(1, Redirect::count());
    }

    public function test_small_yard_and_outdoor_bar_guides_are_published_with_their_photos(): void
    {
        $category = BlogCategory::create(['name' => 'Outdoor Living', 'slug' => 'outdoor-living']);
        $this->service(['name' => 'Balcony & Terrace Decoration', 'slug' => 'balcony-terrace-decoration']);

        $migration = require database_path('migrations/2026_10_28_105419_add_small_yard_pizza_oven_and_outdoor_bar_guides.php');
        $migration->up();
        $migration->up();

        $pizza = BlogPost::where('slug', 'outdoor-pizza-oven-small-yards')->sole();
        $bar = BlogPost::where('slug', 'how-to-build-an-outdoor-bar')->sole();
        $this->assertSame('media/library/narrow-courtyard-string-lights-planters', $pizza->featuredMedia->path);
        $this->assertSame('media/library/wooden-outdoor-bar-thatched-roof', $bar->featuredMedia->path);
        $this->assertSame(7, substr_count($pizza->body, '<figure>'));
        $this->assertSame(10, substr_count($bar->body, '<figure>'));
        $this->assertStringContainsString('/media/library/man-laying-pavers-on-sand-1600.jpg', $bar->body);
        $this->assertContains($category->id, $bar->categories()->pluck('blog_categories.id')->all());
        $this->assertTrue($pizza->isLive());
    }

    public function test_headings_flattened_by_the_visual_editor_are_restored_before_photos_go_in(): void
    {
        $editorBody = '<div>The backyard has evolved.<br><br></div><div><br>But there is a lot to consider.<br><br></div>'
            .'<div><strong><br>Wood-Fired<br></strong><br></div><div>For the purists.<br><br></div><div><br>Thermal mass.<br><br></div>'
            .'<div><strong><br>Who It’s Right For<br></strong><br></div>'
            .'<div><strong>The Culinary Hobbyist:</strong> Sourdough fans.<br><br></div>'
            .'<div><strong><br>The Frequent Host:</strong> Party people.<br><br></div>'
            .'<div><strong><br>FAQ<br></strong><br></div><div><strong>Can I use it in winter?<br></strong><br></div><div>Yes.<br><br></div>';
        $this->blogPost(['slug' => 'outdoor-pizza-oven-pros-and-cons', 'title' => 'Pros', 'body' => $editorBody]);

        (require database_path('migrations/2026_10_28_075730_restore_pizza_oven_post_headings.php'))->up();

        $body = BlogPost::where('slug', 'outdoor-pizza-oven-pros-and-cons')->value('body');
        $this->assertStringStartsWith("<p>The backyard has evolved.</p>\n<p>But there is a lot to consider.</p>\n<h2 id=\"wood-fired\">Wood-Fired</h2>\n<figure>", $body);
        $this->assertStringContainsString('<h2 id="who-its-right-for">Who It’s Right For</h2>', $body);
        $this->assertStringContainsString('<p><strong>The Frequent Host:</strong> Party people.</p>', $body);
        $this->assertStringContainsString("<h2 id=\"faq\">FAQ</h2>\n<h3>Can I use it in winter?</h3>\n<p>Yes.</p>", $body);
        $this->assertStringNotContainsString('<div>', $body);
        $this->assertSame(2, substr_count($body, '<figure>'));
    }
}
