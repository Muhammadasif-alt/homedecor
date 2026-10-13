<?php

namespace App\View;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Media;
use App\Models\PageSection;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\Setting;
use App\Models\Testimonial;

/**
 * Fetches the records a dynamic section needs, so section components stay purely presentational.
 * Returning null for a section means "nothing real to show" and the section is skipped.
 */
class SectionData
{
    public function resolve(PageSection $section): array|null
    {
        $method = 'for'.str($section->type)->studly();

        return method_exists($this, $method) ? $this->{$method}($section) : [];
    }

    private function forServices(PageSection $s): ?array
    {
        $services = Service::published()->ordered()->with('heroMedia')
            ->when($s->get('featured_only'), fn ($q) => $q->where('is_featured', true))
            ->when($s->get('limit'), fn ($q, $limit) => $q->limit($limit))
            ->get();

        return $services->isEmpty() ? null : compact('services');
    }

    private function forProjects(PageSection $s): ?array
    {
        $projects = Project::published()->ordered()->with(['cover', 'category'])
            ->when($s->get('featured_only'), fn ($q) => $q->where('is_featured', true))
            ->limit($s->get('limit', 6))->get();

        return $projects->isEmpty() ? null : compact('projects');
    }

    /**
     * Pairs come from the section's own items (title, before_media_id, after_media_id, url) when set,
     * otherwise from projects that have both a before and an after photo.
     */
    private function forBeforeAfter(PageSection $s): ?array
    {
        if ($items = $s->get('items')) {
            $media = Media::whereIn('id', collect($items)->flatMap(fn ($i) => [$i['before_media_id'] ?? null, $i['after_media_id'] ?? null])->filter())
                ->get()->keyBy('id');
            $pairs = collect($items)
                ->filter(fn ($i) => isset($media[$i['before_media_id'] ?? 0], $media[$i['after_media_id'] ?? 0]))
                ->map(fn ($i) => ['title' => $i['title'] ?? '', 'before' => $media[$i['before_media_id']], 'after' => $media[$i['after_media_id']], 'url' => $i['url'] ?? null])
                ->values();
        } else {
            $pairs = Project::published()->ordered()->with(['beforeMedia', 'afterMedia'])
                ->whereNotNull('before_media_id')->whereNotNull('after_media_id')
                ->limit($s->get('limit', 3))->get()
                ->map(fn (Project $p) => ['title' => $p->title, 'before' => $p->beforeMedia, 'after' => $p->afterMedia, 'url' => route('projects.show', $p)]);
        }

        return $pairs->isEmpty() ? null : compact('pairs');
    }

    private function forPortfolio(PageSection $s): ?array
    {
        $projects = Project::published()->ordered()->with(['cover', 'category'])->get();
        if ($projects->isEmpty()) {
            return null;
        }
        $categories = ProjectCategory::ordered()->whereIn('id', $projects->pluck('project_category_id'))->get();

        return compact('projects', 'categories');
    }

    private function forProcess(PageSection $s): ?array
    {
        $steps = Setting::get('process_steps', []);
        $media = Media::whereIn('id', collect($steps)->pluck('media_id')->filter())->get()->keyBy('id');

        return $steps ? compact('steps', 'media') : null;
    }

    private function forStyles(PageSection $s): ?array
    {
        return $this->itemsWithMedia($s);
    }

    private function forTeam(PageSection $s): ?array
    {
        return $this->itemsWithMedia($s);
    }

    private function forStats(PageSection $s): ?array
    {
        return $s->get('items') ? [] : null;
    }

    private function forGallery(PageSection $s): ?array
    {
        $images = Media::query()
            ->when($s->get('media_ids'), fn ($q, $ids) => $q->whereIn('id', $ids))
            ->when(! $s->get('media_ids') && $s->get('category'), fn ($q) => $q->where('category', $s->get('category')))
            ->limit($s->get('limit', 9))->get();

        return $images->isEmpty() ? null : compact('images');
    }

    private function forTestimonials(PageSection $s): ?array
    {
        $testimonials = Testimonial::approved()->ordered()->with(['photo', 'service'])
            ->when($s->get('limit'), fn ($q, $limit) => $q->limit($limit))->get();

        return $testimonials->isEmpty() ? null : compact('testimonials');
    }

    private function forBlog(PageSection $s): ?array
    {
        $posts = BlogPost::live()->with(['featuredMedia', 'categories'])->latest('published_at')
            ->limit($s->get('limit', 3))->get();

        return $posts->isEmpty() ? null : compact('posts');
    }

    private function forFaqs(PageSection $s): ?array
    {
        $faqs = Faq::published()->ordered()->whereNull('service_id')
            ->when($s->get('scope', 'home') === 'home', fn ($q) => $q->where('show_on_home', true))
            ->get();

        return $faqs->isEmpty() ? null : ['faqs' => $faqs, 'grouped' => $s->get('scope') === 'all'];
    }

    private function forServiceAreas(PageSection $s): ?array
    {
        $areas = ServiceArea::published()->ordered()->get();

        return ($areas->isEmpty() && ! Setting::get('service_area_text')) ? null : compact('areas');
    }

    private function forConsultationForm(PageSection $s): array
    {
        return ['services' => Service::published()->ordered()->get(['id', 'name', 'slug'])];
    }

    private function itemsWithMedia(PageSection $s): ?array
    {
        $items = $s->get('items', []);
        $media = Media::whereIn('id', collect($items)->pluck('media_id')->filter())->get()->keyBy('id');

        return $items ? compact('items', 'media') : null;
    }
}
