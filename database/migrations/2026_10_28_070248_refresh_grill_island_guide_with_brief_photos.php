<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * The owner sent a version of the grill island brief whose image notes describe a photo for every
 * section. Registers nine photos (Unsplash License) that match those notes and re-applies the
 * guide file, which now carries a featured photo and a photo in each main section.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'outdoor-grill-island-under-pergola',
        'stiff-scrubbing-brush-on-paving',
        'hand-checking-hose-brass-fitting',
        'vegetable-skewers-corn-on-grill',
        'dry-leaves-on-grill-grate',
        'grill-in-winter-snow',
        'gloved-hand-wiping-metal-surface',
        'granite-worktop-closeup',
        'stainless-utensils-hanging-rack',
    ];

    public function up(): void
    {
        LibraryPhotoRegistrar::register(self::PHOTOS);
        (new GuidePostSeeder)->syncExisting(['grill-island-maintenance-by-season']);
        Cache::forget('sitemap-xml');
    }

    public function down(): void
    {
        //
    }
};
