<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 11, the last of the outdoor guides: the cobblestone, driveway apron and
 * permeable paver driveway guides were showing villas, plan sketches and building frames.
 * Registers 21 driveway photos (Unsplash License) and re-applies the guide files, which now carry
 * matching photos and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'cobbled-drive-lined-with-trees',
        'rounded-cobblestones-closeup',
        'hexagonal-paving-pattern',
        'wet-stone-setts-joints',
        'cobbled-courtyard-historic-house',
        'weed-between-stone-pavers',
        'broken-paving-stones-in-sand',
        'paver-driveway-meeting-street',
        'concrete-apron-to-garage',
        'asphalt-driveway-large-house',
        'paved-driveway-beside-hedge',
        'asphalt-drive-meeting-road',
        'leaves-blocking-street-drain',
        'drain-grate-concrete',
        'garage-entrance-from-street',
        'clogged-drain-rainy-street',
        'permeable-grass-paver-area',
        'grass-growing-through-paver-grid',
        'interlocking-paver-pattern',
        'grass-strip-between-pavers',
        'grey-block-paved-driveway',
    ];

    private const GUIDES = [
        'cobblestone-driveway-cost',
        'cobblestone-driveway-mistakes',
        'driveway-apron-cost',
        'driveway-apron-maintenance-by-season',
        'driveway-apron-mistakes',
        'how-to-build-a-permeable-paver-driveway',
        'permeable-paver-driveway-pros-and-cons',
    ];

    public function up(): void
    {
        LibraryPhotoRegistrar::register(self::PHOTOS);
        (new GuidePostSeeder)->syncExisting(self::GUIDES);
        Cache::forget('sitemap-xml');
    }

    public function down(): void
    {
        //
    }
};
