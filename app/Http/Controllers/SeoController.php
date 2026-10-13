<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceArea;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public const SITEMAP_CACHE_KEY = 'sitemap-xml';

    public function sitemap(): Response
    {
        $xml = Cache::remember(self::SITEMAP_CACHE_KEY, 3600, fn () => view('seo.sitemap', ['urls' => $this->urls()])->render());

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /thank-you', 'Disallow: /blog?q=', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /']; // never index staging/local

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain']);
    }

    /** @return array<int, array{loc:string, lastmod:?string}> */
    private function urls(): array
    {
        $entry = fn (string $loc, $updated = null) => ['loc' => $loc, 'lastmod' => $updated?->toAtomString()];

        $pages = Page::where('is_published', true)->where('robots_index', true)->get()
            ->map(fn (Page $p) => $entry(match ($p->slug) {
                'home' => route('home'),
                'services' => route('services.index'),
                'gallery' => route('projects.index'),
                'blog' => route('blog.index'),
                'service-areas' => route('areas.index'),
                default => url($p->slug),
            }, $p->updated_at));

        return collect()
            ->merge($pages)
            ->merge(Service::published()->ordered()->get()->map(fn ($s) => $entry($s->url(), $s->updated_at)))
            ->merge(Project::published()->ordered()->get()->map(fn ($p) => $entry($p->url(), $p->updated_at)))
            ->merge(BlogPost::live()->where('robots_index', true)->latest('published_at')->get()->map(fn ($p) => $entry($p->url(), $p->updated_at)))
            ->merge(BlogCategory::whereHas('posts', fn ($q) => $q->live())->get()->map(fn ($c) => $entry(route('blog.category', $c), $c->updated_at)))
            ->merge(ServiceArea::published()->where('has_page', true)->whereNotNull('body')->get()->map(fn ($a) => $entry(route('areas.show', $a), $a->updated_at)))
            ->all();
    }
}
