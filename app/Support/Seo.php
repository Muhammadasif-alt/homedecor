<?php

namespace App\Support;

use App\Models\Media;
use App\Models\Setting;

/**
 * Per-request SEO state: title, meta description, canonical, social image, robots,
 * breadcrumbs and JSON-LD graph. Controllers fill it; the layout renders it.
 */
class Seo
{
    public string $title = '';

    public string $description = '';

    public ?string $canonical = null;

    public ?Media $image = null;

    public string $type = 'website';

    public bool $index = true;

    /** @var array<int, array{name:string, url:string}> */
    public array $breadcrumbs = [];

    /** @var array<int, array> */
    public array $schema = [];

    public static function make(): self
    {
        return app(self::class);
    }

    public function for(object $model, ?string $fallbackDescription = null): self
    {
        $this->title = $model->meta_title ?: ($model->title ?? $model->name ?? '');
        $this->description = $model->meta_description ?: ($fallbackDescription ?? '');
        $this->canonical = ($model->canonical_url ?? null) ?: null;
        $this->image = $model->ogMedia ?? null;
        if (isset($model->robots_index)) {
            $this->index = (bool) $model->robots_index;
        }

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(?string $description): self
    {
        if ($description) {
            $this->description = $description;
        }

        return $this;
    }

    public function image(?Media $media): self
    {
        $this->image ??= $media;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function noindex(): self
    {
        $this->index = false;

        return $this;
    }

    public function canonical(string $url): self
    {
        $this->canonical ??= $url;

        return $this;
    }

    /** @param array<string, string> $crumbs label => url (Home is added automatically) */
    public function breadcrumbs(array $crumbs): self
    {
        $this->breadcrumbs = [['name' => 'Home', 'url' => route('home')]];
        foreach ($crumbs as $name => $url) {
            $this->breadcrumbs[] = ['name' => $name, 'url' => $url];
        }

        return $this->schema([
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($this->breadcrumbs)->values()->map(fn ($c, $i) => [
                '@type' => 'ListItem', 'position' => $i + 1, 'name' => $c['name'], 'item' => $c['url'],
            ])->all(),
        ]);
    }

    public function schema(array $node): self
    {
        $this->schema[] = $node;

        return $this;
    }

    /* ---------- Rendering helpers ---------- */

    public function fullTitle(): string
    {
        $suffix = Setting::get('seo_title_suffix', ' | '.Setting::get('site_name', config('app.name')));

        return str_contains($this->title, trim(ltrim($suffix, ' |'))) ? $this->title : $this->title.$suffix;
    }

    public function metaDescription(): string
    {
        return str($this->description ?: Setting::get('seo_default_description', ''))->squish()->limit(160)->toString();
    }

    public function canonicalUrl(): string
    {
        // Pagination keeps ?page=N in the canonical; other query strings are dropped.
        $page = (int) request('page');

        return $this->canonical ?: url()->current().($page > 1 ? '?page='.$page : '');
    }

    public function imageUrl(): ?string
    {
        $media = $this->image ?? Media::find(Setting::get('seo_default_og_media_id'));

        return $media?->url(1600);
    }

    public function jsonLd(): ?string
    {
        if (! $this->schema) {
            return null;
        }

        return json_encode(
            ['@context' => 'https://schema.org', '@graph' => $this->schema],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
        );
    }
}
