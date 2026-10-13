<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NavigationItemRequest;
use App\Models\NavigationItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NavigationItemController extends ResourceController
{
    protected static string $model = NavigationItem::class;

    protected static string $parameter = 'navigationItem';

    public static function middleware(): array
    {
        return [...parent::middleware(), new Middleware('can:update,navigationItem', only: ['move'])];
    }

    public function index(): View
    {
        $menus = NavigationItem::ordered()->get()->groupBy('menu');

        return view('admin.navigation.index', compact('menus'));
    }

    public function create(Request $request): View
    {
        $menu = array_key_exists($request->query('menu'), NavigationItem::MENUS) ? $request->query('menu') : 'header';

        return view('admin.navigation.form', ['item' => new NavigationItem([
            'menu' => $menu,
            'is_enabled' => true,
            'sort_order' => (int) NavigationItem::where('menu', $menu)->max('sort_order') + 1,
        ])]);
    }

    public function store(NavigationItemRequest $request): RedirectResponse
    {
        NavigationItem::create($request->payload());

        return $this->done('admin.navigation.index', [], 'Menu link added.');
    }

    public function edit(NavigationItem $navigationItem): View
    {
        return view('admin.navigation.form', ['item' => $navigationItem]);
    }

    public function update(NavigationItemRequest $request, NavigationItem $navigationItem): RedirectResponse
    {
        $navigationItem->update($request->payload());

        return $this->done('admin.navigation.index', [], 'Menu link saved.');
    }

    public function destroy(NavigationItem $navigationItem): RedirectResponse
    {
        $navigationItem->delete();

        return $this->done('admin.navigation.index', [], 'Menu link deleted.');
    }

    /** Move a link one place up/down within its menu (renumbers sort_order). */
    public function move(Request $request, NavigationItem $navigationItem): RedirectResponse
    {
        $direction = $request->validate(['direction' => ['required', Rule::in(['up', 'down'])]])['direction'];

        $ids = NavigationItem::where('menu', $navigationItem->menu)->ordered()->pluck('id')->all();
        $index = array_search($navigationItem->id, $ids, true);
        $target = $direction === 'up' ? $index - 1 : $index + 1;

        if ($index !== false && isset($ids[$target])) {
            [$ids[$index], $ids[$target]] = [$ids[$target], $ids[$index]];
            DB::transaction(fn () => collect($ids)->each(
                fn ($id, $position) => NavigationItem::whereKey($id)->update(['sort_order' => $position + 1])
            ));
        }

        return $this->done('admin.navigation.index', [], 'Menu order updated.');
    }
}
