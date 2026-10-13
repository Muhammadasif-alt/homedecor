<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Support\SectionSchema;
use App\Http\Requests\Admin\PageSectionRequest;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Sections belong to a page: managing them requires permission to update the page.
 */
class PageSectionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('can:update,page')];
    }

    public function store(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate(['type' => ['required', Rule::in(array_keys(PageSection::TYPES))]]);

        $section = $page->sections()->create([
            'type' => $validated['type'],
            'is_enabled' => false, // start hidden until content is filled in
            'sort_order' => (int) $page->sections()->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.pages.sections.edit', [$page, $section])
            ->with('success', 'Section added (hidden). Fill it in and tick “Show on page” when ready.');
    }

    public function edit(Page $page, PageSection $section): View
    {
        return view('admin.sections.edit', [
            'page' => $page,
            'section' => $section,
            'fields' => SectionSchema::fields($section->type),
            'extra' => SectionSchema::extra($section->type, $section->data),
        ]);
    }

    public function update(PageSectionRequest $request, Page $page, PageSection $section): RedirectResponse
    {
        $section->update($request->payload());

        return $this->done('admin.pages.sections.edit', [$page, $section], 'Section saved.');
    }

    public function destroy(Page $page, PageSection $section): RedirectResponse
    {
        $section->delete();

        return $this->done('admin.pages.edit', $page, 'Section deleted.');
    }

    public function toggle(Page $page, PageSection $section): RedirectResponse
    {
        $section->update(['is_enabled' => ! $section->is_enabled]);

        return $this->done('admin.pages.edit', $page, $section->is_enabled ? 'Section shown.' : 'Section hidden.');
    }

    /** Move a section one place up or down; sort_order is renumbered 1..n. */
    public function move(Request $request, Page $page, PageSection $section): RedirectResponse
    {
        $direction = $request->validate(['direction' => ['required', Rule::in(['up', 'down'])]])['direction'];

        $ids = $page->sections()->pluck('id')->all();
        $index = array_search($section->id, $ids, true);
        $target = $direction === 'up' ? $index - 1 : $index + 1;

        if ($index !== false && isset($ids[$target])) {
            [$ids[$index], $ids[$target]] = [$ids[$target], $ids[$index]];
            DB::transaction(function () use ($ids) {
                foreach ($ids as $position => $id) {
                    PageSection::whereKey($id)->update(['sort_order' => $position + 1]);
                }
            });
        }

        return $this->done('admin.pages.edit', $page, 'Section order updated.');
    }
}
