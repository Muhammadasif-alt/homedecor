<?php

namespace Tests\Feature\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Media;
use App\Support\PastedArticle;

class PasteArticleTest extends AdminTestCase
{
    private const BRIEF = <<<'MD'
[IMAGE: A stone grill island at sunset.]

# Grill Island Care Through the Year

A grill island is the heart of the garden. It needs care in every season to stay safe and good-looking.

## 1. Spring Awakening

[IMAGE: Gloved hands scrubbing a grill.]

Spring is the time to inspect everything.

*   **Leak test:** Brush soapy water on the connections.

***

## Frequently Asked Questions

**1. How often should I clean the grates?**
After every cook, with a deep clean each season.

**2. Can I pressure wash the island?**
It is best avoided.
MD;

    private function media(string $slug): Media
    {
        return Media::create([
            'path' => "media/library/{$slug}", 'alt' => "Alt for {$slug}", 'category' => 'outdoor',
            'width' => 2400, 'height' => 1800, 'variants' => [480, 960, 1600, 2400],
        ]);
    }

    public function test_parser_turns_a_brief_into_sections_faq_and_photo_slots(): void
    {
        $article = PastedArticle::parse(self::BRIEF);
        $html = $article->html();

        $this->assertSame('Grill Island Care Through the Year', $article->title);
        $this->assertSame(['A stone grill island at sunset.', 'Gloved hands scrubbing a grill.'], $article->images);
        $this->assertStringContainsString('<h2 id="spring-awakening">1. Spring Awakening</h2>', $html);
        $this->assertStringContainsString('<h2 id="faq">FAQ</h2>', $html);
        $this->assertStringContainsString('<h3>How often should I clean the grates?</h3>', $html);
        $this->assertStringNotContainsString('<h1', $html);
        $this->assertStringNotContainsString('<hr', $html);
        $this->assertStringNotContainsString('@@SLOT', $html);
        $this->assertSame('A grill island is the heart of the garden. It needs care in every season to stay safe and good-looking.', $article->excerpt());
    }

    public function test_preview_lists_a_photo_slot_for_each_image_note(): void
    {
        $this->actingAs($this->editor())
            ->post(route('admin.blog-posts.import.preview'), ['text' => self::BRIEF])
            ->assertOk()
            ->assertSee('Featured photo')
            ->assertSee('Gloved hands scrubbing a grill.')
            ->assertSee('value="Grill Island Care Through the Year"', false)
            ->assertSee('Spring is the time to inspect everything.');
    }

    public function test_publishing_creates_a_live_article_with_featured_and_inline_photos(): void
    {
        $featured = $this->media('stone-grill-island');
        $inline = $this->media('gloved-hands-scrubbing');
        $category = BlogCategory::create(['name' => 'Outdoor', 'slug' => 'outdoor']);

        $this->actingAs($this->editor())->post(route('admin.blog-posts.import.store'), [
            'text' => self::BRIEF,
            'title' => 'Grill Island Care Through the Year',
            'slug' => '',
            'excerpt' => 'A grill island needs care in every season.',
            'meta_description' => 'Seasonal care for a grill island.',
            'status' => 'published',
            'categories' => [$category->id],
            'slots' => [
                ['media_id' => $featured->id],
                ['media_id' => $inline->id, 'caption' => 'Scrub the grill in spring.'],
            ],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $post = BlogPost::where('slug', 'grill-island-care-through-the-year')->firstOrFail();
        $this->assertTrue($post->isLive());
        $this->assertSame($featured->id, $post->featured_media_id);
        $this->assertSame(1, substr_count($post->body, '<figure>'));
        $this->assertStringContainsString('/media/library/gloved-hands-scrubbing-1600.jpg', $post->body);
        $this->assertStringContainsString('<figcaption>Scrub the grill in spring.</figcaption>', $post->body);
        $this->assertStringContainsString('id="faq"', $post->body);
        $this->assertSame([$category->id], $post->categories()->pluck('blog_categories.id')->all());

        $this->get(route('blog.show', $post))->assertOk()->assertSee('Grill Island Care Through the Year');
    }

    public function test_replacing_an_existing_article_keeps_its_slug_and_publish_date(): void
    {
        $old = $this->media('old-photo');
        $post = BlogPost::create([
            'slug' => 'grill-care', 'title' => 'Old title', 'excerpt' => 'Old', 'body' => '<p>Old body</p>',
            'status' => 'published', 'published_at' => now()->subMonth(), 'featured_media_id' => $old->id,
        ]);
        $publishedAt = $post->published_at;

        $this->actingAs($this->editor())->post(route('admin.blog-posts.import.store'), [
            'post_id' => $post->id,
            'text' => self::BRIEF,
            'title' => 'Grill Island Care Through the Year',
            'slug' => 'grill-care',
            'excerpt' => 'New excerpt.',
            'status' => 'published',
        ])->assertSessionHasNoErrors();

        $post->refresh();
        $this->assertSame(1, BlogPost::count());
        $this->assertSame('Grill Island Care Through the Year', $post->title);
        $this->assertStringNotContainsString('Old body', $post->body);
        $this->assertSame($old->id, $post->featured_media_id, 'With no new featured photo the old one is kept.');
        $this->assertTrue($post->published_at->equalTo($publishedAt));
    }

    public function test_slug_clash_with_another_article_is_rejected(): void
    {
        BlogPost::create(['slug' => 'taken', 'title' => 'Taken', 'excerpt' => 'x', 'body' => '<p>x</p>', 'status' => 'draft']);

        $this->actingAs($this->editor())->post(route('admin.blog-posts.import.store'), [
            'text' => self::BRIEF, 'title' => 'Taken', 'slug' => 'taken', 'excerpt' => 'x', 'status' => 'draft',
        ])->assertSessionHasErrors('slug');

        $this->assertSame(1, BlogPost::count());
    }

    public function test_empty_paste_is_rejected(): void
    {
        $this->actingAs($this->editor())
            ->post(route('admin.blog-posts.import.preview'), ['text' => ''])
            ->assertSessionHasErrors('text');
    }

    public function test_guests_cannot_use_the_paste_page(): void
    {
        $this->get(route('admin.blog-posts.import'))->assertRedirect();
        $this->post(route('admin.blog-posts.import.store'), ['text' => self::BRIEF])->assertRedirect();
        $this->assertSame(0, BlogPost::count());
    }

    public function test_index_and_edit_pages_link_to_the_paste_page(): void
    {
        $editor = $this->editor();
        $post = BlogPost::create(['slug' => 'a', 'title' => 'A', 'excerpt' => 'x', 'body' => '<p>x</p>', 'status' => 'draft']);

        $this->actingAs($editor)->get(route('admin.blog-posts.index'))->assertOk()->assertSee('Paste article');
        $this->actingAs($editor)->get(route('admin.blog-posts.edit', $post))->assertOk()->assertSee('Replace by pasting');
        $this->actingAs($editor)->get(route('admin.blog-posts.import', ['post' => $post->id]))->assertOk()->assertSee('Paste the article');
    }
}
