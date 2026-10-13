<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Setting;
use App\Support\SchemaFactory;
use App\Support\Seo;
use Illuminate\View\View;

class PageController extends Controller
{
    /** Pages that have their own controller and must not be served through the catch-all. */
    private const RESERVED = ['home', 'services', 'gallery', 'blog', 'service-areas'];

    public function home(Seo $seo): View
    {
        $page = Page::findBySlug('home');
        $seo->for($page)->image($page->section('hero')?->media)->schema([
            '@type' => 'WebSite',
            'name' => Setting::get('site_name', config('app.name')),
            'url' => route('home'),
        ]);

        return view('pages.show', compact('page'));
    }

    public function show(string $slug, Seo $seo, SchemaFactory $schema): View
    {
        abort_if(in_array($slug, self::RESERVED, true), 404);

        $page = Page::findBySlug($slug);
        $seo->for($page)
            ->image($page->section('hero')?->media)
            ->breadcrumbs([$page->title => url($slug)]);

        if ($page->template === 'legal') {
            return view('pages.legal', compact('page'));
        }

        if ($page->section('faqs')?->get('scope') === 'all') {
            $seo->schema($schema->faqPage(Faq::published()->whereNull('service_id')->ordered()->get()));
        }

        return view('pages.show', compact('page'));
    }
}
