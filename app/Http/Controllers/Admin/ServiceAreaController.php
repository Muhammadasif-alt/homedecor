<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServiceAreaRequest;
use App\Models\ServiceArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceAreaController extends ResourceController
{
    protected static string $model = ServiceArea::class;

    protected static string $parameter = 'serviceArea';

    public function index(Request $request): View
    {
        $areas = ServiceArea::query()
            ->when($request->query('q'), fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->ordered()->paginate(50)->withQueryString();

        return view('admin.service-areas.index', compact('areas'));
    }

    public function create(): View
    {
        return view('admin.service-areas.form', ['area' => new ServiceArea([
            'is_published' => true,
            'sort_order' => (int) ServiceArea::max('sort_order') + 1,
        ])]);
    }

    public function store(ServiceAreaRequest $request): RedirectResponse
    {
        ServiceArea::create($request->payload());

        return $this->done('admin.service-areas.index', [], 'Service area added.');
    }

    public function edit(ServiceArea $serviceArea): View
    {
        return view('admin.service-areas.form', ['area' => $serviceArea]);
    }

    public function update(ServiceAreaRequest $request, ServiceArea $serviceArea): RedirectResponse
    {
        $serviceArea->update($request->payload());

        return $this->done('admin.service-areas.index', [], 'Service area saved.');
    }

    public function destroy(ServiceArea $serviceArea): RedirectResponse
    {
        $serviceArea->delete();

        return $this->done('admin.service-areas.index', [], 'Service area deleted. Consider adding a redirect if it had a page.');
    }
}
