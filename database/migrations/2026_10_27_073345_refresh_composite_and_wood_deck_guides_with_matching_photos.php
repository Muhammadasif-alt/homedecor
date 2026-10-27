<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 5: the composite and wood deck guides were showing laptops, site plans, villas
 * and balconies. Registers 23 deck photos (Unsplash License) and re-applies the guide files, which
 * now carry a deck featured photo, deck section photos, numbered sections and a corrected fire pit FAQ.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'light-composite-deck-lounge-furniture',
        'grey-composite-deck-boards-grain',
        'builders-framing-new-deck',
        'grey-deck-metal-railing-pool',
        'composite-deck-modern-house',
        'frost-on-deck-boards',
        'composite-deck-dining-table',
        'barbecue-on-small-deck',
        'aerial-view-deck-furniture',
        'composite-boards-even-gaps',
        'deck-boards-screw-heads',
        'cedar-deck-railings-forest',
        'wide-hardwood-deck-house',
        'large-grey-deck-backyard',
        'raised-deck-with-railings',
        'timber-deck-pergola-dining',
        'house-deck-overlooking-lawn',
        'weathered-deck-rainwater',
        'old-weathered-deck-house',
        'small-deck-sunlit-garden',
        'diagonal-deck-planks',
        'potted-plants-dark-deck-corner',
        'deck-dining-beside-tall-fence',
    ];

    private const GUIDES = [
        'composite-deck-cost',
        'composite-deck-maintenance-by-season',
        'composite-deck-mistakes',
        'wood-deck-cost',
        'wood-deck-pros-and-cons',
        'small-yard-wood-deck-ideas',
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
