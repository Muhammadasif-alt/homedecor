<?php

namespace Tests\Feature\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogPostTest extends AdminTestCase
{
    public function test_future_publish_date_schedules_the_post(): void
    {
        $editor = $this->editor();
        $category = BlogCategory::create(['name' => 'Guides', 'slug' => 'guides']);
        $publishAt = now()->addDays(5)->startOfMinute();

        $this->actingAs($editor)->post(route('admin.blog-posts.store'), [
            'title' => 'Choosing curtain fabrics',
            'excerpt' => 'How to pick linen, velvet or sheers.',
            'body' => '<div>Start with the light.</div>',
            'status' => 'published',
            'published_at' => $publishAt->format('Y-m-d\TH:i'),
            'categories' => [$category->id],
            'robots_index' => '1',
        ])->assertSessionHasNoErrors();

        $post = BlogPost::where('slug', 'choosing-curtain-fabrics')->firstOrFail();
        $this->assertSame('published', $post->status);
        $this->assertTrue($post->published_at->equalTo($publishAt));
        $this->assertTrue($post->published_at->isFuture());
        $this->assertFalse($post->isLive());
        $this->assertSame($editor->id, $post->author_id);
        $this->assertSame([$category->id], $post->categories()->pluck('blog_categories.id')->all());

        $this->actingAs($editor)->get(route('admin.blog-posts.index'))->assertOk()->assertSee('Scheduled');
    }

    public function test_publishing_without_a_date_publishes_now(): void
    {
        $this->actingAs($this->editor())->post(route('admin.blog-posts.store'), [
            'title' => 'Small space ideas',
            'excerpt' => 'Ideas.',
            'body' => '<div>Text</div>',
            'status' => 'published',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(BlogPost::sole()->isLive());
    }
}
