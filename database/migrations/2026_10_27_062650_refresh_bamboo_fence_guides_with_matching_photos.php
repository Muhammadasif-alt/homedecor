<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 1: the three bamboo fence guides were showing indoor photos. Registers twelve
 * bamboo photos (Unsplash License) and re-applies the guide files, which now carry a bamboo
 * featured photo, three section photos each and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'bamboo-fence-before-bamboo-grove',
        'thick-walled-bamboo-poles',
        'weathered-grey-bamboo-fence-poppies',
        'bamboo-fence-black-cord-ties',
        'bamboo-cane-fence-garden',
        'bamboo-roll-screen-wired',
        'living-bamboo-stem-closeup',
        'bamboo-fence-post-rope-ties',
        'bamboo-fence-japanese-garden',
        'sunlit-bamboo-wall-shadows',
        'weathered-canes-wire-ties',
        'new-golden-bamboo-fence',
    ];

    private const GUIDES = ['bamboo-fence-mistakes', 'bamboo-fence-cost', 'bamboo-fence-maintenance-by-season'];

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
