<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 10: the concrete, gravel and paver driveway guides were showing building
 * frames, site plans, aerial estates and villas. Registers 22 driveway photos (Unsplash License)
 * and re-applies the guide files, which now carry matching photos and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'concrete-driveway-modern-house',
        'pressure-washing-paving',
        'crack-in-concrete-surface',
        'snow-blower-clearing-driveway',
        'concrete-driveway-control-joints',
        'workers-pouring-concrete-wheelbarrow',
        'workers-placing-concrete-over-mesh',
        'gravel-driveway-between-hedges',
        'crushed-stone-aggregate-closeup',
        'roller-compacting-sub-base',
        'gravel-driveway-in-garden',
        'potholes-filled-with-water',
        'snow-covered-driveway-trees',
        'fresh-gravel-top-dressing',
        'gravel-drive-between-fences',
        'block-paved-driveway-brick-house',
        'laying-concrete-block-pavers',
        'granite-setts-with-joints',
        'pallets-of-concrete-pavers',
        'paved-driveway-thatched-house',
        'paved-drive-to-brick-house',
        'weeds-at-paving-edge',
    ];

    private const GUIDES = [
        'concrete-driveway-maintenance-by-season',
        'concrete-driveway-mistakes',
        'gravel-driveway-cost',
        'gravel-driveway-maintenance-by-season',
        'gravel-driveway-mistakes',
        'how-to-build-a-paver-driveway',
        'paver-driveway-pros-and-cons',
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
