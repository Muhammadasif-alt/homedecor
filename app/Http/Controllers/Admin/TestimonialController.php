<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends ResourceController
{
    protected static string $model = Testimonial::class;

    protected static string $parameter = 'testimonial';

    public function index(Request $request): View
    {
        $testimonials = Testimonial::query()
            ->with(['service:id,name', 'photo'])
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('customer_name', 'like', "%{$term}%")->orWhere('content', 'like', "%{$term}%")))
            ->when($request->filled('approved'), fn ($q) => $q->where('is_approved', $request->query('approved') === '1'))
            ->ordered()
            ->paginate(25)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return $this->form(new Testimonial(['sort_order' => (int) Testimonial::max('sort_order') + 1]));
    }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        Testimonial::create($request->payload());

        return $this->done('admin.testimonials.index', [], 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return $this->form($testimonial);
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($request->payload());

        return $this->done('admin.testimonials.index', [], 'Testimonial saved.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return $this->done('admin.testimonials.index', [], 'Testimonial deleted.');
    }

    private function form(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => $testimonial,
            'serviceOptions' => Service::ordered()->pluck('name', 'id'),
            'projectOptions' => Project::ordered()->pluck('title', 'id'),
        ]);
    }
}
