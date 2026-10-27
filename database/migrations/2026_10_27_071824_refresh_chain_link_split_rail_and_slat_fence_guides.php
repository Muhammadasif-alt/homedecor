<?php

use App\Support\LibraryPhotoRegistrar;
use Database\Seeders\GuidePostSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Outdoor batch 4: the chain link, split rail and horizontal slat fence guides were showing
 * scaffolding, site plans and study rooms. Registers twenty fence photos (Unsplash License) and
 * re-applies the guide files, which now carry a fence featured photo, section photos and numbered sections.
 */
return new class extends Migration
{
    private const PHOTOS = [
        'chain-link-fence-green-field',
        'chain-link-mesh-see-through',
        'chain-link-gate-padlock',
        'dog-behind-chain-link-fence',
        'foliage-over-green-chain-link',
        'black-coated-chain-link-fence',
        'roses-on-chain-link-fence',
        'flowers-through-chain-link',
        'chain-link-fence-top-rail-posts',
        'chain-link-mesh-closeup',
        'post-and-rail-fence-long-field',
        'weathered-split-rail-post',
        'rustic-zigzag-rail-fence',
        'grey-weathered-rail-fence',
        'modern-horizontal-slat-fence-conifers',
        'weathered-horizontal-fence-planks',
        'timber-slats-closeup',
        'small-garden-wooden-boundary-fence',
        'black-slatted-fence-tree',
        'wooden-fence-solar-lights',
    ];

    private const GUIDES = [
        'chain-link-fence-pros-and-cons',
        'chain-link-fence-small-yard-ideas',
        'how-to-build-a-chain-link-fence',
        'split-rail-fence-mistakes',
        'horizontal-slat-fence-pros-and-cons',
        'horizontal-slat-fence-small-yard-ideas',
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
