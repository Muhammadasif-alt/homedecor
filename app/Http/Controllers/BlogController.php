<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Page;
use App\Support\SchemaFactory;
use App\Support\Seo;
use App\Support\TableOfContents;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request, Seo $seo): View
    {
        $page = Page::findBySlug('blog');
        $search = trim((string) $request->query('q'));

        $posts = $this->listing()
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")))
            ->paginate(9)->withQueryString();

        $seo->for($page)
            ->image($page->section('hero')?->media)
            ->breadcrumbs(['Blog' => route('blog.index')]);
        if ($search) {
            $seo->noindex(); // search result pages shouldn't be indexed
        }

        return view('blog.index', [
            'page' => $page,
            'posts' => $posts,
            'search' => $search,
            'categories' => $this->categories(),
            'activeCategory' => null,
        ]);
    }

    public function category(BlogCategory $category, Seo $seo): View
    {
        $page = Page::findBySlug('blog');
        $posts = $this->listing()
            ->whereHas('categories', fn ($q) => $q->whereKey($category->id))
            ->paginate(9);

        $seo->title($category->meta_title ?: $category->name.' Ideas & Advice')
            ->description($category->meta_description ?: $category->description)
            ->image($page->section('hero')?->media)
            ->breadcrumbs(['Blog' => route('blog.index'), $category->name => route('blog.category', $category)]);

        return view('blog.index', [
            'page' => $page,
            'posts' => $posts,
            'search' => '',
            'categories' => $this->categories(),
            'activeCategory' => $category,
        ]);
    }

    /** One template renders every article. */
    public function show(BlogPost $post, Seo $seo, SchemaFactory $schema): View
    {
        abort_unless($post->isLive() || auth()->check(), 404);

        $post->load(['featuredMedia', 'author', 'categories', 'ogMedia', 'service.heroMedia']);
        [$body, $toc] = TableOfContents::build($post->body);

        $related = BlogPost::live()->whereKeyNot($post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('blog_categories.id', $post->categories->pluck('id')))
            ->with('featuredMedia')->latest('published_at')->limit(3)->get();
        if ($related->count() < 3) {
            $related = $related->merge(BlogPost::live()->whereKeyNot($post->id)->whereNotIn('id', $related->pluck('id'))
                ->with('featuredMedia')->latest('published_at')->limit(3 - $related->count())->get());
        }

        $seo->for($post, $post->excerpt)
            ->type('article')
            ->image($post->featuredMedia)
            ->breadcrumbs(['Blog' => route('blog.index'), $post->title => $post->url()])
            ->schema($schema->blogPosting($post));
        if (! $post->isLive()) {
            $seo->noindex(); // admin previewing a draft
        }

        return view('blog.show', compact('post', 'body', 'toc', 'related'));
    }

    private function listing()
    {
        return BlogPost::live()->with(['featuredMedia', 'categories'])->latest('published_at');
    }

    private function categories()
    {
        return BlogCategory::ordered()->whereHas('posts', fn ($q) => $q->live())
            ->withCount(['posts' => fn ($q) => $q->live()])->get();
    }
}
