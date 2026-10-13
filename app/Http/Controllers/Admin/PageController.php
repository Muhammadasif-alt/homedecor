<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends ResourceController
{
    protected static string $model = Page::class;

    protected static string $parameter = 'page';

    public function index(Request $request): View
    {
        $pages = Page::query()
            ->withCount('sections')
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")->orWhere('slug', 'like', "%{$term}%")))
            ->when($request->query('template'), fn ($q, $template) => $q->where('template', $template))
            ->orderBy('title')
            ->paginate(25)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.form', ['page' => new Page(['template' => 'default', 'is_published' => true, 'robots_index' => true])]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = Page::create($request->payload());

        return $this->done('admin.pages.edit', $page, 'Page created. Add sections below.');
    }

    public function edit(Page $page): View
    {
        $page->load('sections.media');

        return view('admin.pages.form', [
            'page' => $page,
            'types' => PageSection::TYPES,
        ]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->payload());

        return $this->done('admin.pages.edit', $page, 'Page saved.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return $this->done('admin.pages.index', [], "Page “{$page->title}” moved to trash.");
    }
}
