<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class BlogPostRequest extends AdminRequest
{
    public const STATUSES = ['draft' => 'Draft', 'published' => 'Published'];

    protected array $booleans = ['robots_index'];

    protected array $richText = ['body'];

    protected ?string $slugFrom = 'title';

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts', 'slug')->ignore($this->route('blogPost'))],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:300000'],
            'featured_media_id' => $this->mediaRule(),
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'status' => ['required', Rule::in(array_keys(self::STATUSES))],
            'published_at' => ['nullable', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:blog_categories,id'],
            ...$this->seoRules(canonical: true, og: true, robots: true),
        ];
    }

    public function attributes(): array
    {
        return parent::attributes() + ['published_at' => 'publish date', 'featured_media_id' => 'featured image'];
    }

    /** Publishing without a date publishes now; a future date schedules the post. */
    protected function transform(array $data): array
    {
        $data['published_at'] = filled($data['published_at'] ?? null) ? Carbon::parse($data['published_at']) : null;
        if ($data['status'] === 'published' && ! $data['published_at']) {
            $data['published_at'] = now();
        }

        return Arr::except($data, ['categories']);
    }

    public function categoryIds(): array
    {
        return collect($this->validated('categories') ?? [])->map(fn ($id) => (int) $id)->unique()->values()->all();
    }
}
