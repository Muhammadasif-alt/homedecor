<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 7: the deck skirting and patio-deck combo guides were showing site plans,
 * scaffolding, villas and study rooms. Registers 19 deck, skirting and patio photos (Unsplash
 * License) and re-applies the guide files, which now carry matching photos and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'multi-level-deck-boxed-skirting',
        'timber-lattice-panel-closeup',
        'cedar-deck-board-skirting-planters',
        'deck-steps-white-boxed-sides',
        'vertical-dark-timber-boards-deck',
        'deck-horizontal-white-boards',
        'raised-deck-open-underneath',
        'weathered-peeling-lattice',
        'deck-meeting-stone-wall-garden',
        'stacked-concrete-paving-blocks',
        'curved-paved-seating-area-steps',
        'curved-steps-to-paved-patio',
        'stone-patio-loungers-lawn',
        'stone-patio-wooden-chairs',
        'circular-paved-area-steps',
        'small-garden-patio-pots-seating',
        'sunken-circular-paved-patio',
        'planters-on-stone-patio-wall',
        'string-lights-over-patio',
    ];

    private const GUIDES = [
        'deck-skirting-cost',
        'deck-skirting-ideas-small-yards',
        'deck-skirting-mistakes',
        'how-to-build-a-patio-deck-combo',
        'patio-deck-combo-pros-and-cons',
        'patio-deck-combo-small-yards',
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
