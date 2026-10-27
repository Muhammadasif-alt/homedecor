<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 2: the aluminium and wood fence guides were showing office, plan-drawing and
 * house-exterior photos. Registers twenty fence photos (Unsplash License) and re-applies the
 * guide files, which now carry a fence featured photo, three section photos each and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'black-metal-fence-park-lawn',
        'ornate-black-gold-metal-fence',
        'black-fence-spear-finials-garden',
        'metal-fence-following-curved-path',
        'black-metal-fence-green-garden',
        'plants-growing-through-metal-fence',
        'dark-metal-fence-pointed-finials',
        'black-metal-fence-clean-bars',
        'metal-fence-line-sunset',
        'metal-fence-on-sloping-wall',
        'decorative-metal-fence-scrollwork',
        'street-metal-fence-brick-building',
        'new-wood-fence-lattice-gate',
        'weathered-wooden-fence-post',
        'wood-fence-boards-grass',
        'stained-wood-fence-boards',
        'house-with-wooden-fence-garden',
        'tall-wooden-privacy-fence-snow',
        'weathered-peeling-fence-boards',
        'weathered-wooden-rail-fence',
    ];

    private const GUIDES = [
        'aluminium-fence-cost',
        'aluminium-fence-maintenance-by-season',
        'aluminium-fence-mistakes',
        'how-to-build-a-wood-fence',
        'wood-fence-pros-and-cons',
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
