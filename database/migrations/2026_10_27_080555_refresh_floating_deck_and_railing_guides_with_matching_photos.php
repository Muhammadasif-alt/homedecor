<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 6: the floating deck and deck railing guides were showing site plans, villas,
 * balconies and house exteriors. Registers 22 deck and railing photos (Unsplash License) and
 * re-applies the guide files, which now carry matching photos, numbered sections and a corrected
 * fire pit FAQ.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'low-garden-deck-under-tree',
        'builders-fitting-deck-joists',
        'weathered-dark-deck-boards',
        'timber-deck-in-woodland-garden',
        'low-deck-with-rail-garden',
        'builders-framing-deck-boards',
        'cutting-deck-board-to-length',
        'low-deck-hot-tub-planting',
        'low-timber-platform-on-gravel',
        'flowering-planter-on-deck',
        'multi-level-cedar-deck-planters',
        'house-with-large-railed-deck',
        'cedar-deck-railings-aerial',
        'cable-railing-sea-view',
        'glass-railing-deck-umbrella',
        'glass-railing-deck-wicker-chairs',
        'snow-on-dark-deck-railing',
        'black-metal-stair-railing',
        'sunlit-timber-balcony-railing',
        'deck-glass-railing-forest-house',
        'glass-balustrade-deck-stairs',
        'railed-timber-stairs-woodland',
    ];

    private const GUIDES = [
        'floating-deck-pros-and-cons',
        'how-to-build-a-floating-deck',
        'floating-deck-ideas-small-yards',
        'deck-railing-cost',
        'deck-railing-maintenance-by-season',
        'deck-railing-mistakes',
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
