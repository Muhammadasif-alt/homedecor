<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProjectCategoryRequest;
use App\Models\ProjectCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectCategoryController extends ResourceController
{
    protected static string $model = ProjectCategory::class;

    protected static string $parameter = 'projectCategory';

    public function index(Request $request): View
    {
        $categories = ProjectCategory::withCount('projects')
            ->when($request->query('q'), fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->ordered()->paginate(50)->withQueryString();

        return view('admin.project-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.project-categories.form', ['category' => new ProjectCategory(['sort_order' => (int) ProjectCategory::max('sort_order') + 1])]);
    }

    public function store(ProjectCategoryRequest $request): RedirectResponse
    {
        ProjectCategory::create($request->payload());

        return $this->done('admin.project-categories.index', [], 'Category created.');
    }

    public function edit(ProjectCategory $projectCategory): View
    {
        return view('admin.project-categories.form', ['category' => $projectCategory]);
    }

    public function update(ProjectCategoryRequest $request, ProjectCategory $projectCategory): RedirectResponse
    {
        $projectCategory->update($request->payload());

        return $this->done('admin.project-categories.index', [], 'Category saved.');
    }

    public function destroy(ProjectCategory $projectCategory): RedirectResponse
    {
        $projectCategory->delete();

        return $this->done('admin.project-categories.index', [], 'Category deleted. Its projects are now uncategorised.');
    }
}
