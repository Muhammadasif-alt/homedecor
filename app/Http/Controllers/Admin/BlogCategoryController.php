<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCategoryController extends ResourceController
{
    protected static string $model = BlogCategory::class;

    protected static string $parameter = 'blogCategory';

    public function index(Request $request): View
    {
        $categories = BlogCategory::withCount('posts')
            ->when($request->query('q'), fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->ordered()->paginate(50)->withQueryString();

        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.blog-categories.form', ['category' => new BlogCategory(['sort_order' => (int) BlogCategory::max('sort_order') + 1])]);
    }

    public function store(BlogCategoryRequest $request): RedirectResponse
    {
        BlogCategory::create($request->payload());

        return $this->done('admin.blog-categories.index', [], 'Category created.');
    }

    public function edit(BlogCategory $blogCategory): View
    {
        return view('admin.blog-categories.form', ['category' => $blogCategory]);
    }

    public function update(BlogCategoryRequest $request, BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->update($request->payload());

        return $this->done('admin.blog-categories.index', [], 'Category saved.');
    }

    public function destroy(BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->delete();

        return $this->done('admin.blog-categories.index', [], 'Category deleted.');
    }
}
