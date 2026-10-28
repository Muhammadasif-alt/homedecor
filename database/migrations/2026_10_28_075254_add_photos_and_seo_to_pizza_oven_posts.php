<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Media;
use App\Models\Redirect;
use App\Models\Service;
use App\Support\ArticleFigure;
use App\Support\LibraryPhotoRegistrar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * The two pizza oven articles were pasted through the admin with no photos of their own, unrelated
 * patio and driveway photos as featured images, cut-off meta descriptions and no category, service
 * or internal links. Registers sixteen matching photos (Unsplash License), places one under each
 * section they fit, fills the SEO fields, shortens both slugs with 301s from the old addresses and
 * links the pair to each other.
 */
return new class extends Migration
{
    /**
     * @var array<string, array{
     *     slug: string, meta_title: string, meta_description: string, excerpt: string, featured: string,
     *     figures: array<string, array{0: string, 1: string}>, replace: array<string, string>
     * }>
     */
    private const POSTS = [
        'how-to-build-an-outdoor-pizza-oven-step-by-step-for-diyers' => [
            'slug' => 'how-to-build-an-outdoor-pizza-oven',
            'meta_title' => 'How to Build an Outdoor Pizza Oven: 9 DIY Steps | Home Decor Studio',
            'meta_description' => 'Build a wood-fired pizza oven step by step: concrete slab, block stand, firebrick hearth, brick dome, insulation, chimney, render, curing and first firing.',
            'excerpt' => 'A step-by-step guide to building a wood-fired pizza oven: from the concrete slab and block stand to the firebrick hearth, brick dome, insulation, chimney, render and a slow cure.',
            'featured' => 'white-dome-pizza-oven-garden',
            'figures' => [
                'foundation' => ['rebar-grid-before-concrete-pour', 'Rebar sits in the middle of the slab, not on the dirt.'],
                'stand' => ['laying-concrete-blocks-with-mortar', 'Check every course of blocks with a level.'],
                'hearth' => ['stacked-red-oven-bricks', 'Use refractory firebricks for the cooking floor, never house bricks.'],
                'dome' => ['brick-dome-oven-wood-fire', 'A finished brick dome with its entry arch.'],
                'chimney' => ['pizza-oven-with-chimney-flue', 'The flue sits at the front, outside the inner dome.'],
                'curing' => ['small-fire-inside-brick-dome', 'Cure with small fires first and grow them day by day.'],
                'firing' => ['pizza-on-metal-peel-at-oven-mouth', 'A metal peel turns and lifts the pizza.'],
            ],
            'replace' => [
                'your own backyard masterpiece.</p>' => 'your own backyard masterpiece.</p>'."\n".'<p>Not sure an oven suits your garden yet? Weigh it up first with our guide to <a href="/blog/outdoor-pizza-oven-pros-and-cons">outdoor pizza oven pros and cons</a>.</p>',
            ],
        ],
        'outdoor-pizza-oven-pros-cons-and-who-its-right-for' => [
            'slug' => 'outdoor-pizza-oven-pros-and-cons',
            'meta_title' => 'Outdoor Pizza Oven Pros and Cons | Home Decor Studio',
            'meta_description' => 'Wood-fired or gas, heat, learning curve, upkeep, portable models and real costs: the pros and cons of an outdoor pizza oven and who it suits.',
            'excerpt' => 'Wood-fired or gas, heat, learning curve, upkeep, portable models and real costs: what owning an outdoor pizza oven is like and who it suits best.',
            'featured' => 'pizza-baking-beside-wood-flames',
            'figures' => [
                'wood-fired' => ['tiled-dome-oven-with-wood-fire', 'A wood fire heats the dome and floor of the oven.'],
                'gas-powered' => ['pizza-under-gas-flame', 'A gas burner gives heat at the turn of a dial.'],
                'heat-intensity' => ['leopard-spotted-neapolitan-pizza', 'High heat puffs the crust and leaves leopard spots.'],
                'social-hub' => ['pizzas-shared-beside-outdoor-oven', 'Pizza night turns the oven into the centre of the party.'],
                'learning-curve' => ['launching-pizza-into-clay-oven', 'Launching the pizza off the peel takes practice.'],
                'portability' => ['portable-pizza-oven-on-table', 'Portable ovens sit on a table and pack away after.'],
                'who-its-right-for' => ['chef-beside-tiled-pizza-oven', 'Keen cooks and frequent hosts get the most from an oven.'],
            ],
            'replace' => [
                'Brands like Ooni, Gozney, and Roccbox have' => 'Brands like Ooni and Gozney (maker of the Roccbox) have',
                'can degrade the insulation.</p>' => 'can degrade the insulation.</p>'."\n".'<p>If the oven sits in an outdoor kitchen, the same routine as our <a href="/blog/grill-island-maintenance-by-season">seasonal grill island care plan</a> keeps the stone and steel around it in good shape.</p>',
                'to your initial budget.</p>' => 'to your initial budget.</p>'."\n".'<p>Building a masonry oven yourself costs far less than a custom one. Our <a href="/blog/how-to-build-an-outdoor-pizza-oven">step-by-step pizza oven build guide</a> walks through all nine stages.</p>',
            ],
        ],
    ];

    public function up(): void
    {
        $photos = collect(self::POSTS)
            ->flatMap(fn (array $post) => [$post['featured'], ...array_column($post['figures'], 0)])
            ->unique()->values()->all();
        LibraryPhotoRegistrar::register($photos);

        $media = Media::whereIn('path', array_map(fn ($slug) => "media/library/{$slug}", $photos))->get()->keyBy('path');
        $categories = BlogCategory::where('slug', 'outdoor-living')->pluck('id')->all();
        $serviceId = Service::where('slug', 'balcony-terrace-decoration')->value('id');

        foreach (self::POSTS as $oldSlug => $config) {
            $post = BlogPost::whereIn('slug', [$oldSlug, $config['slug']])->first();
            if (! $post) {
                continue;
            }

            $body = $post->body;
            if (! str_contains($body, '<figure>')) {
                $body = strtr($body, $config['replace']);
                foreach ($config['figures'] as $headingId => [$slug, $caption]) {
                    $photo = $media->get("media/library/{$slug}");
                    $heading = preg_quote('<h2 id="'.$headingId.'">', '/');
                    if ($photo) {
                        $body = preg_replace("/({$heading}.*?<\/h2>)/s", '$1'."\n".ArticleFigure::html($photo, $caption), $body, 1);
                    }
                }
            }

            $post->update([
                'slug' => $config['slug'],
                'body' => $body,
                'excerpt' => $config['excerpt'],
                'meta_title' => $config['meta_title'],
                'meta_description' => $config['meta_description'],
                'featured_media_id' => $media->get("media/library/{$config['featured']}")?->id ?? $post->featured_media_id,
                'og_media_id' => null,
                'service_id' => $post->service_id ?? $serviceId,
            ]);
            if ($categories) {
                $post->categories()->syncWithoutDetaching($categories);
            }

            if ($oldSlug !== $config['slug']) {
                Redirect::firstOrCreate(['from_path' => "/blog/{$oldSlug}"], ['to_url' => "/blog/{$config['slug']}", 'status_code' => 301]);
            }
        }

        Cache::forget('sitemap-xml');
    }

    public function down(): void
    {
        //
    }
};
