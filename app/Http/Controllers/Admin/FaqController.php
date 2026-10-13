<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends ResourceController
{
    protected static string $model = Faq::class;

    protected static string $parameter = 'faq';

    public function index(Request $request): View
    {
        $faqs = Faq::query()
            ->with('service:id,name')
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('question', 'like', "%{$term}%")->orWhere('answer', 'like', "%{$term}%")))
            ->when($request->query('service'), fn ($q, $service) => $service === 'general'
                ? $q->whereNull('service_id')
                : $q->where('service_id', $service))
            ->when($request->query('category'), fn ($q, $category) => $q->where('category', $category))
            ->ordered()
            ->paginate(30)->withQueryString();

        return view('admin.faqs.index', [
            'faqs' => $faqs,
            'serviceFilter' => ['general' => 'General (no service)'] + Service::ordered()->pluck('name', 'id')->all(),
        ]);
    }

    public function create(Request $request): View
    {
        return $this->form(new Faq([
            'service_id' => $request->integer('service_id') ?: null,
            'category' => $request->integer('service_id') ? 'services' : 'general',
            'is_published' => true,
            'sort_order' => (int) Faq::max('sort_order') + 1,
        ]));
    }

    public function store(FaqRequest $request): RedirectResponse
    {
        $faq = Faq::create($request->payload());

        return $this->done('admin.faqs.index', $faq->service_id ? ['service' => $faq->service_id] : [], 'FAQ added.');
    }

    public function edit(Faq $faq): View
    {
        return $this->form($faq);
    }

    public function update(FaqRequest $request, Faq $faq): RedirectResponse
    {
        $faq->update($request->payload());

        return $this->done('admin.faqs.index', $faq->service_id ? ['service' => $faq->service_id] : [], 'FAQ saved.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return $this->done('admin.faqs.index', [], 'FAQ deleted.');
    }

    private function form(Faq $faq): View
    {
        return view('admin.faqs.form', [
            'faq' => $faq,
            'serviceOptions' => Service::ordered()->pluck('name', 'id'),
        ]);
    }
}
