<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 9: the stone and steel edging guides were showing villas, cost estimates and
 * finishing-stage building sites. Registers 16 edging and garden photos (Unsplash License) and
 * re-applies the guide files, which now carry matching photos and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'stone-steps-and-edged-beds',
        'mixed-natural-stones-closeup',
        'dry-laid-fieldstone-edge',
        'planting-against-stone-edge',
        'pond-with-stone-edging',
        'moss-on-stone-edging',
        'stonework-with-ground-cover',
        'straight-edged-gravel-path-arches',
        'weathered-steel-with-planting',
        'black-powder-coated-planters',
        'galvanised-steel-trough-planter',
        'gravel-path-through-planted-garden',
        'path-with-neat-planted-edges',
        'curved-gravel-path-garden',
        'curved-gravel-drive-lawn-edge',
        'pebbles-meeting-concrete-edge',
    ];

    private const GUIDES = [
        'how-to-build-stone-edging',
        'stone-edging-maintenance-by-season',
        'steel-edging-cost',
        'steel-edging-maintenance-by-season',
        'steel-edging-mistakes',
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
