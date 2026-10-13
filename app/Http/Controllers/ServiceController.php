<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use App\Support\SchemaFactory;
use App\Support\Seo;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Seo $seo): View
    {
        $page = Page::findBySlug('services');
        $seo->for($page)
            ->image($page->section('hero')?->media)
            ->breadcrumbs(['Services' => route('services.index')]);

        return view('pages.show', compact('page'));
    }

    /** One template renders every service — content comes from the database. */
    public function show(Service $service, Seo $seo, SchemaFactory $schema): View
    {
        abort_unless($service->is_published, 404);

        $service->load([
            'heroMedia', 'ogMedia', 'sections.media', 'gallery', 'faqs', 'testimonials',
            'related' => fn ($q) => $q->published()->ordered()->with('heroMedia'),
            'projects' => fn ($q) => $q->published()->ordered()->with(['cover', 'category', 'beforeMedia', 'afterMedia']),
        ]);

        $process = $service->process ?: Setting::get('process_steps', []);

        // Further reading: hand-picked articles first, then articles written about this service.
        $live = fn ($q) => $q->live()->with('featuredMedia');
        $articles = $service->articles()->tap($live)->get()
            ->merge($service->posts()->tap($live)->latest('published_at')->limit(3)->get())
            ->unique('id')->take(3)->values();

        $seo->for($service, $service->short_description)
            ->image($service->heroMedia)
            ->breadcrumbs(['Services' => route('services.index'), $service->name => $service->url()])
            ->schema($schema->service($service));

        if ($service->faqs->isNotEmpty()) {
            $seo->schema($schema->faqPage($service->faqs));
        }

        return view('services.show', compact('service', 'process', 'articles'));
    }
}
