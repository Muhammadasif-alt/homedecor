<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 8: the landscape, paver and brick edging guides were showing desk plans, site
 * plans, villas and concrete frames. Registers 18 edging and paving photos (Unsplash License) and
 * re-applies the guide files, which now carry matching photos and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'edged-flower-bed-in-lawn',
        'curved-path-through-garden-borders',
        'wood-chip-mulch-closeup',
        'mulch-meeting-white-stone-border',
        'brick-path-with-raised-stone-beds',
        'stone-slab-edge-beside-lawn',
        'concrete-kerb-edging-planting-bed',
        'clean-lawn-edge-beside-path',
        'paved-path-with-edge-restraint',
        'grey-interlocking-concrete-pavers',
        'hexagon-paver-path-through-grass',
        'stone-paver-path-flower-border',
        'brick-pavers-meeting-concrete-edge',
        'brick-edging-between-lawn-and-path',
        'weeds-between-weathered-bricks',
        'brick-path-beside-stone-wall',
        'laid-red-bricks-pattern',
        'red-brick-path-open-joints',
    ];

    private const GUIDES = [
        'how-to-build-landscape-edging',
        'landscape-edging-pros-and-cons',
        'how-to-build-paver-edging',
        'paver-edging-pros-and-cons',
        'brick-edging-pros-and-cons',
        'how-to-build-brick-edging',
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
