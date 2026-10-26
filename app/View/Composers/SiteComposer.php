<?php

namespace App\View\Composers;

use App\Models\NavigationItem;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\Setting;
use App\Support\Seo;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Shares site-wide data (settings, menus, services for the mega menu) with the public layout.
 */
class SiteComposer
{
    public const NAV_CACHE_KEY = 'public-nav';

    public function compose(View $view): void
    {
        $nav = Cache::remember(self::NAV_CACHE_KEY, 3600, function (): array {
            $areas = ServiceArea::published()->ordered()->get(['id', 'name', 'slug', 'has_page']);

            return [
                'menus' => NavigationItem::where('is_enabled', true)->ordered()->get()
                    ->reject(fn (NavigationItem $item) => $areas->isEmpty() && $item->pointsTo(route('areas.index')))
                    ->groupBy('menu'),
                'services' => Service::published()->ordered()->with('heroMedia')->get(['id', 'name', 'slug', 'icon', 'short_description', 'hero_media_id']),
                'areas' => $areas,
            ];
        });

        $site = Setting::all_values();
        $site['site_name'] = ($site['site_name'] ?? '') ?: config('app.name');

        $view->with([
            'site' => $site,
            'menus' => $nav['menus'],
            'menuServices' => $nav['services'],
            'serviceAreas' => $nav['areas'],
            'seo' => Seo::make(),
        ]);
    }
}
