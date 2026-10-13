<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProjectController extends ResourceController
{
    protected static string $model = Project::class;

    protected static string $parameter = 'project';

    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with(['cover', 'category'])
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")->orWhere('location', 'like', "%{$term}%")))
            ->when($request->query('category'), fn ($q, $id) => $q->where('project_category_id', $id))
            ->when($request->filled('status'), fn ($q) => $q->where('is_published', $request->query('status') === 'published'))
            ->ordered()
            ->paginate(25)->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'categories' => ProjectCategory::ordered()->pluck('name', 'id'),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Project(['is_published' => true, 'sort_order' => (int) Project::max('sort_order') + 1]));
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $project = DB::transaction(function () use ($request) {
            $project = Project::create($request->payload());
            $this->syncRelations($project, $request);

            return $project;
        });

        return $this->done('admin.projects.edit', $project, 'Project created.');
    }

    public function edit(Project $project): View
    {
        $project->load(['images', 'services:id']);

        return $this->form($project);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        DB::transaction(function () use ($request, $project) {
            $project->update($request->payload());
            $this->syncRelations($project, $request);
        });

        return $this->done('admin.projects.edit', $project, 'Project saved.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return $this->done('admin.projects.index', [], "Project “{$project->title}” moved to trash.");
    }

    private function form(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'categories' => ProjectCategory::ordered()->pluck('name', 'id'),
            'serviceOptions' => Service::ordered()->pluck('name', 'id'),
        ]);
    }

    private function syncRelations(Project $project, ProjectRequest $request): void
    {
        $project->images()->delete();
        $project->images()->createMany($request->images());
        $project->services()->sync($request->serviceIds());
    }
}
