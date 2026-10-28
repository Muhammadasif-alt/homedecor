<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdminRequest;
use App\Http\Requests\Admin\PreviewPastedArticleRequest;
use App\Http\Requests\Admin\PublishPastedArticleRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Media;
use App\Models\Service;
use App\Support\PastedArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * "Paste article": the owner pastes a whole article (as written in their briefs), picks a photo
 * for each [IMAGE: …] note, and publishes it as a new post or over an existing one.
 */
class BlogImportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('can:create,'.BlogPost::class)];
    }

    public function create(Request $request): View
    {
        $post = $request->integer('post') ? BlogPost::find($request->integer('post')) : null;
        if ($post) {
            $this->authorize('update', $post);
        }

        return view('admin.blog-import.create', [
            'post' => $post,
            'postOptions' => BlogPost::query()->orderBy('title')->pluck('title', 'id'),
        ]);
    }

    public function preview(PreviewPastedArticleRequest $request): View
    {
        $post = $this->target($request->validated('post_id'));
        $article = PastedArticle::parse($request->validated('text'));

        return view('admin.blog-import.preview', [
            'post' => $post,
            'text' => $request->validated('text'),
            'article' => $article,
            'html' => AdminRequest::purify($article->html()),
            'defaults' => [
                'title' => $article->title,
                'slug' => $post?->slug ?? Str::slug($article->title),
                'excerpt' => $article->excerpt(),
                'meta_title' => $post?->meta_title,
                'meta_description' => $article->metaDescription(),
                'status' => $post?->status ?? 'published',
                'service_id' => $post?->service_id,
                'categories' => $post ? $post->categories()->pluck('blog_categories.id')->all() : [],
                'featured_media_id' => $post?->featured_media_id,
            ],
            'categoryOptions' => BlogCategory::ordered()->pluck('name', 'id'),
            'serviceOptions' => Service::ordered()->pluck('name', 'id'),
        ]);
    }

    public function store(PublishPastedArticleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $post = $this->target($data['post_id'] ?? null);
        $article = PastedArticle::parse($data['text']);

        $slots = collect($data['slots'] ?? [])->map(fn ($slot) => [
            'media' => filled($slot['media_id'] ?? null) ? Media::find($slot['media_id']) : null,
            'caption' => $slot['caption'] ?? null,
        ])->all();

        $attributes = [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'body' => AdminRequest::purify($article->html($slots)),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'status' => $data['status'],
            'service_id' => $data['service_id'] ?? null,
            'featured_media_id' => $slots[0]['media']?->id ?? $post?->featured_media_id,
        ];

        $post = DB::transaction(function () use ($post, $attributes, $request) {
            if ($post) {
                $post->update($attributes + [
                    'published_at' => $attributes['status'] === 'published' ? ($post->published_at ?? now()) : $post->published_at,
                ]);
            } else {
                $post = BlogPost::create($attributes + [
                    'author_id' => $request->user()->id,
                    'published_at' => $attributes['status'] === 'published' ? now() : null,
                    'robots_index' => true,
                ]);
            }
            $post->categories()->sync($request->categoryIds());

            return $post;
        });

        $message = $post->isLive() ? 'Article published and live.' : 'Article saved as a draft.';

        return $this->done('admin.blog-posts.edit', $post, $message);
    }

    private function target(?int $id): ?BlogPost
    {
        $post = $id ? BlogPost::findOrFail($id) : null;
        if ($post) {
            $this->authorize('update', $post);
        }

        return $post;
    }
}
