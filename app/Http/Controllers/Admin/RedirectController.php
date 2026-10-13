<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RedirectRequest;
use App\Models\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RedirectController extends ResourceController
{
    protected static string $model = Redirect::class;

    protected static string $parameter = 'redirect';

    public function index(Request $request): View
    {
        $redirects = Redirect::query()
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('from_path', 'like', "%{$term}%")->orWhere('to_url', 'like', "%{$term}%")))
            ->orderBy('from_path')
            ->paginate(50)->withQueryString();

        return view('admin.redirects.index', compact('redirects'));
    }

    public function create(): View
    {
        return view('admin.redirects.form', ['redirect' => new Redirect(['status_code' => 301])]);
    }

    public function store(RedirectRequest $request): RedirectResponse
    {
        Redirect::create($request->payload());

        return $this->done('admin.redirects.index', [], 'Redirect added.');
    }

    public function edit(Redirect $redirect): View
    {
        return view('admin.redirects.form', compact('redirect'));
    }

    public function update(RedirectRequest $request, Redirect $redirect): RedirectResponse
    {
        $redirect->update($request->payload());

        return $this->done('admin.redirects.index', [], 'Redirect saved.');
    }

    public function destroy(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();

        return $this->done('admin.redirects.index', [], 'Redirect deleted.');
    }
}
