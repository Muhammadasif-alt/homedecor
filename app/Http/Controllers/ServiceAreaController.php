<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\ServiceArea;
use App\Support\Seo;
use Illuminate\View\View;

class ServiceAreaController extends Controller
{
    public function index(Seo $seo): View
    {
        $page = Page::findBySlug('service-areas');
        $seo->for($page)
            ->image($page->section('hero')?->media)
            ->breadcrumbs(['Service Areas' => route('areas.index')]);

        if (ServiceArea::published()->doesntExist()) {
            $seo->noindex();
        }

        return view('pages.show', compact('page'));
    }

    /** Detail pages exist only for real areas the admin has given unique content (has_page). */
    public function show(ServiceArea $area, Seo $seo): View
    {
        abort_unless($area->is_published && $area->has_page && filled($area->body), 404);

        $seo->title($area->meta_title ?: 'Home Decor Services in '.$area->name)
            ->description($area->meta_description ?: $area->summary)
            ->breadcrumbs(['Service Areas' => route('areas.index'), $area->name => route('areas.show', $area)]);

        return view('areas.show', compact('area'));
    }
}
