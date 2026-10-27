<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 3: the privacy and vinyl fence guides were showing garden, balcony and villa
 * photos with no fence in them. Registers twenty fence photos (Unsplash License) and re-applies
 * the guide files, which now carry a fence featured photo, section photos and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'close-boarded-timber-fence-top',
        'stained-timber-privacy-boards',
        'woven-horizontal-timber-fence',
        'neighbouring-timber-fences-meeting',
        'white-privacy-fence-beside-house',
        'peeling-paint-picket-fence',
        'neat-garden-fence-raised-planters',
        'fence-covered-in-climbing-plants',
        'backyard-fence-with-planter-boxes',
        'hanging-pots-on-lattice-fence',
        'vine-climbing-lattice-fence-top',
        'bamboo-screen-flowering-shrub',
        'long-white-vinyl-style-fence',
        'white-fence-in-winter',
        'white-picket-fence-hedge',
        'white-rail-fence-beside-path',
        'white-picket-fence-yellow-shrubs',
        'pink-roses-on-white-fence',
        'blue-picket-fence-flower-bed',
        'slim-white-rail-fence-field',
    ];

    private const GUIDES = [
        'privacy-fence-cost',
        'privacy-fence-mistakes',
        'privacy-fence-small-yard-ideas',
        'vinyl-fence-pros-and-cons',
        'vinyl-fence-small-yard-ideas',
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
