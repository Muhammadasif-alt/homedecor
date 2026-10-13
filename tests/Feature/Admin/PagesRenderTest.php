<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\PageSection;

/**
 * Smoke test: every admin screen renders for an administrator.
 */
class PagesRenderTest extends AdminTestCase
{
    public function test_index_and_create_screens_render(): void
    {
        $admin = $this->admin();

        $routes = ['admin.dashboard', 'admin.settings.edit'];
        foreach (['pages', 'services', 'projects', 'project-categories', 'blog-posts', 'blog-categories', 'testimonials',
            'faqs', 'navigation', 'service-areas', 'redirects', 'users'] as $module) {
            $routes[] = "admin.{$module}.index";
            $routes[] = "admin.{$module}.create";
        }
        $routes[] = 'admin.media.index';
        $routes[] = 'admin.inquiries.index';

        foreach ($routes as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_edit_screens_render_with_related_data(): void
    {
        $admin = $this->admin();
        $category = \App\Models\ProjectCategory::create(['name' => 'Living rooms', 'slug' => 'living-rooms']);
        $service = \App\Models\Service::create(['name' => 'Styling', 'slug' => 'styling', 'short_description' => 'x',
            'hero_title' => 'x', 'hero_description' => 'x', 'intro' => '<p>x</p>', 'included' => [['title' => 'A', 'text' => 'B']]]);
        $project = \App\Models\Project::create(['title' => 'Marina flat', 'slug' => 'marina-flat', 'summary' => 'x', 'project_category_id' => $category->id]);
        $project->services()->attach($service);
        $blogCategory = \App\Models\BlogCategory::create(['name' => 'Guides', 'slug' => 'guides']);
        $post = \App\Models\BlogPost::create(['title' => 'Post', 'slug' => 'post', 'excerpt' => 'x', 'body' => 'x', 'author_id' => $admin->id]);
        $post->categories()->attach($blogCategory);

        $screens = [
            route('admin.services.edit', $service),
            route('admin.projects.edit', $project),
            route('admin.project-categories.edit', $category),
            route('admin.blog-posts.edit', $post),
            route('admin.blog-categories.edit', $blogCategory),
            route('admin.testimonials.edit', \App\Models\Testimonial::create(['customer_name' => 'Lina', 'content' => 'Lovely', 'service_id' => $service->id])),
            route('admin.faqs.edit', \App\Models\Faq::create(['question' => 'Q?', 'answer' => 'A', 'service_id' => $service->id])),
            route('admin.navigation.edit', \App\Models\NavigationItem::create(['menu' => 'header', 'label' => 'Home', 'url' => '/'])),
            route('admin.service-areas.edit', \App\Models\ServiceArea::create(['name' => 'Marina', 'slug' => 'marina'])),
            route('admin.redirects.edit', \App\Models\Redirect::create(['from_path' => '/old', 'to_url' => '/new'])),
            route('admin.users.edit', $admin),
        ];

        foreach ($screens as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
        $this->actingAs($admin)->get(route('admin.projects.index'))->assertSee('Marina flat');
        $this->actingAs($admin)->get(route('admin.blog-posts.index'))->assertSee('Guides');
    }

    public function test_page_sections_can_be_added_edited_reordered_and_toggled(): void
    {
        $admin = $this->admin();
        $page = Page::create(['slug' => 'about-us', 'title' => 'About us']);

        $this->actingAs($admin)->get(route('admin.pages.edit', $page))->assertOk();

        foreach (['hero', 'features', 'gallery'] as $type) {
            $this->actingAs($admin)->post(route('admin.pages.sections.store', $page), ['type' => $type])->assertRedirect();
        }
        [$hero, $features, $gallery] = $page->sections()->get()->all();

        foreach ([$hero, $features, $gallery] as $section) {
            $this->actingAs($admin)->get(route('admin.pages.sections.edit', [$page, $section]))->assertOk();
        }

        $this->actingAs($admin)->put(route('admin.pages.sections.update', [$page, $hero]), [
            'heading' => 'Calm, layered homes',
            'is_enabled' => '1',
            'data' => [
                'buttons' => [['label' => 'Book', 'url' => '/book-consultation', 'style' => 'primary']],
                'size' => 'full',
            ],
            'data_json' => '{"custom": 1}',
        ])->assertSessionHasNoErrors();

        $hero->refresh();
        $this->assertTrue($hero->is_enabled);
        $this->assertSame('/book-consultation', $hero->get('buttons.0.url'));
        $this->assertSame(1, $hero->get('custom'));

        $this->actingAs($admin)->put(route('admin.pages.sections.update', [$page, $hero]), ['data_json' => '[1,2'])
            ->assertSessionHasErrors('data_json');

        $this->actingAs($admin)->patch(route('admin.pages.sections.move', [$page, $gallery]), ['direction' => 'up']);
        $this->assertSame([$hero->id, $gallery->id, $features->id], $page->sections()->pluck('id')->all());

        $this->actingAs($admin)->patch(route('admin.pages.sections.toggle', [$page, $hero]));
        $this->assertFalse($hero->fresh()->is_enabled);

        $this->actingAs($admin)->delete(route('admin.pages.sections.destroy', [$page, $features]));
        $this->assertModelMissing($features);
        $this->assertInstanceOf(PageSection::class, $gallery->fresh());
    }
}
