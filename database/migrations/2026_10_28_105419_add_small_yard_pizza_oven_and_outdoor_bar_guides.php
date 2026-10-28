<?php

use App\Models\BlogCategory;
use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Publishes the owner's small-yard pizza oven and outdoor bar briefs as guides. Registers the
 * eighteen photos (Unsplash License) chosen to match the briefs' image notes from their committed
 * variants, then lets the create-only guide seeder add the two new articles.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'narrow-courtyard-string-lights-planters',
        'man-using-portable-pizza-oven',
        'smoke-rising-from-tall-steel-flue',
        'wheeled-pellet-cooker-on-deck',
        'built-in-stone-cooker-brick-chimney',
        'white-rendered-outdoor-kitchen-stools',
        'stone-slab-bench-on-paving',
        'string-lights-over-patio-dining',
        'wooden-outdoor-bar-thatched-roof',
        'floor-plan-sketching',
        'stacked-sawn-timber-boards',
        'man-laying-pavers-on-sand',
        'timber-stud-wall-framing',
        'nail-gun-fixing-timber-planks',
        'concrete-bar-counter-stools',
        'brush-applying-wood-stain',
        'led-strip-under-bar-counter',
        'woven-bar-stools-at-outdoor-bar',
        'hand-wiping-wooden-counter',
    ];

    public function up(): void
    {
        LibraryPhotoRegistrar::register(self::PHOTOS);

        // A fresh database gets every guide from DatabaseSeeder, once categories and services exist.
        if (BlogCategory::exists()) {
            (new GuidePostSeeder)->createMissing(['outdoor-pizza-oven-small-yards', 'how-to-build-an-outdoor-bar']);
        }

        Cache::forget('sitemap-xml');
    }

    public function down(): void
    {
        //
    }
};
