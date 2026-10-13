<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Faq;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServiceController extends ResourceController
{
    protected static string $model = Service::class;

    protected static string $parameter = 'service';

    public function index(Request $request): View
    {
        $services = Service::query()
            ->with('heroMedia')
            ->when($request->query('q'), fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->when($request->filled('status'), fn ($q) => $q->where('is_published', $request->query('status') === 'published'))
            ->ordered()
            ->paginate(25)->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return $this->form(new Service(['is_published' => true, 'sort_order' => (int) Service::max('sort_order') + 1]));
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $service = DB::transaction(function () use ($request) {
            $service = Service::create($request->payload());
            $this->syncRelations($service, $request);

            return $service;
        });

        return $this->done('admin.services.edit', $service, 'Service created.');
    }

    public function edit(Service $service): View
    {
        $service->load(['sections', 'gallery', 'related:id', 'projects:id']);

        return $this->form($service);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        DB::transaction(function () use ($request, $service) {
            $service->update($request->payload());
            $this->syncRelations($service, $request);
        });

        return $this->done('admin.services.edit', $service, 'Service saved.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return $this->done('admin.services.index', [], "Service “{$service->name}” moved to trash.");
    }

    private function form(Service $service): View
    {
        return view('admin.services.form', [
            'service' => $service,
            'serviceOptions' => Service::ordered()->whereKeyNot($service->id ?? 0)->pluck('name', 'id'),
            'projectOptions' => Project::ordered()->pluck('title', 'id'),
            'faqCount' => $service->exists ? Faq::where('service_id', $service->id)->count() : 0,
        ]);
    }

    private function syncRelations(Service $service, ServiceRequest $request): void
    {
        $keep = [];
        foreach ($request->sections() as $row) {
            $attributes = collect($row)->except('id')->all();
            $section = $row['id'] ? $service->sections()->whereKey($row['id'])->first() : null;
            if ($section) {
                $section->update($attributes);
            } else {
                $section = $service->sections()->create($attributes);
            }
            $keep[] = $section->id;
        }
        $service->sections()->whereNotIn('id', $keep)->delete();

        $service->gallery()->sync(
            collect($request->ids('gallery'))->mapWithKeys(fn ($id, $i) => [$id => ['sort_order' => $i]])->all()
        );
        $service->related()->sync($request->ids('related'));
        $service->projects()->sync($request->ids('projects'));
    }
}
