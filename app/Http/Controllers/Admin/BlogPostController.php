<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogPostController extends ResourceController
{
    protected static string $model = BlogPost::class;

    protected static string $parameter = 'blogPost';

    public function index(Request $request): View
    {
        $posts = BlogPost::query()
            ->with(['featuredMedia', 'author:id,name', 'categories:id,name'])
            ->when($request->query('q'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->when($request->query('category'), fn ($q, $id) => $q->whereHas('categories', fn ($q) => $q->whereKey($id)))
            ->when($request->query('status'), fn ($q, $status) => match ($status) {
                'scheduled' => $q->where('status', 'published')->where('published_at', '>', now()),
                'published' => $q->live(),
                default => $q->where('status', 'draft'),
            })
            ->latest('updated_at')
            ->paginate(25)->withQueryString();

        return view('admin.blog-posts.index', [
            'posts' => $posts,
            'categories' => BlogCategory::ordered()->pluck('name', 'id'),
        ]);
    }

    public function create(): View
    {
        return $this->form(new BlogPost(['status' => 'draft', 'robots_index' => true]));
    }

    public function store(BlogPostRequest $request): RedirectResponse
    {
        $post = DB::transaction(function () use ($request) {
            $post = BlogPost::create($request->payload() + ['author_id' => $request->user()->id]);
            $post->categories()->sync($request->categoryIds());

            return $post;
        });

        return $this->done('admin.blog-posts.edit', $post, $this->savedMessage($post, 'created'));
    }

    public function edit(BlogPost $blogPost): View
    {
        $blogPost->load(['categories:id', 'author:id,name']);

        return $this->form($blogPost);
    }

    public function update(BlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        DB::transaction(function () use ($request, $blogPost) {
            $blogPost->update($request->payload());
            $blogPost->categories()->sync($request->categoryIds());
        });

        return $this->done('admin.blog-posts.edit', $blogPost, $this->savedMessage($blogPost, 'saved'));
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->delete();

        return $this->done('admin.blog-posts.index', [], "Article “{$blogPost->title}” moved to trash.");
    }

    private function form(BlogPost $post): View
    {
        return view('admin.blog-posts.form', [
            'post' => $post,
            'categoryOptions' => BlogCategory::ordered()->pluck('name', 'id'),
            'serviceOptions' => Service::ordered()->pluck('name', 'id'),
        ]);
    }

    private function savedMessage(BlogPost $post, string $verb): string
    {
        if ($post->status === 'published' && $post->published_at?->isFuture()) {
            return "Article {$verb} and scheduled for {$post->published_at->format('j M Y, H:i')}.";
        }

        return "Article {$verb}.";
    }
}
