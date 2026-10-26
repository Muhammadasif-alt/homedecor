<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * The footer linked to /testimonials, which has no route and returned 404 on every page.
 * Disables that menu item; an admin can re-enable it once a testimonials page exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('navigation_items')->where('url', '/testimonials')->update(['is_enabled' => false]);
        Cache::forget('public-nav');
    }

    public function down(): void
    {
        DB::table('navigation_items')->where('url', '/testimonials')->update(['is_enabled' => true]);
        Cache::forget('public-nav');
    }
};
