<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use App\Support\Seo;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Seo $seo): View
    {
        $page = Page::findBySlug('gallery');
        $seo->for($page)
            ->image($page->section('hero')?->media)
            ->breadcrumbs(['Gallery' => route('projects.index')]);

        return view('pages.show', compact('page'));
    }

    /** One template renders every portfolio project. */
    public function show(Project $project, Seo $seo): View
    {
        abort_unless($project->is_published, 404);

        $project->load(['cover', 'category', 'images.media', 'beforeMedia', 'afterMedia', 'testimonials', 'ogMedia',
            'services' => fn ($q) => $q->published()->ordered()]);

        $related = Project::published()->ordered()
            ->whereKeyNot($project->id)
            ->where('project_category_id', $project->project_category_id)
            ->with(['cover', 'category'])
            ->limit(3)->get();

        if ($related->count() < 3) {
            $related = $related->merge(
                Project::published()->ordered()->whereKeyNot($project->id)->whereNotIn('id', $related->pluck('id'))
                    ->with(['cover', 'category'])->limit(3 - $related->count())->get()
            );
        }

        $seo->for($project, $project->summary)
            ->image($project->cover)
            ->breadcrumbs(['Gallery' => route('projects.index'), $project->title => $project->url()]);

        return view('projects.show', compact('project', 'related'));
    }
}
