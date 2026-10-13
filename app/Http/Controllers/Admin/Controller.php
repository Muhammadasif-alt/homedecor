<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;

    /** Cache keys the public site uses for data that admin edits can change. */
    protected const PUBLIC_CACHE_KEYS = ['public-nav', 'home-page', 'why-choose-us', 'sitemap-xml', 'site-settings'];

    protected function flushPublicCache(): void
    {
        foreach (self::PUBLIC_CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }

    /** Flush public caches and redirect with a success flash message. */
    protected function done(string $route, mixed $parameters, string $message): RedirectResponse
    {
        $this->flushPublicCache();

        return redirect()->route($route, $parameters)->with('success', $message);
    }
}
