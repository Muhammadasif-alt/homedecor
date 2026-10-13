<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ProjectRequest extends AdminRequest
{
    public const RICH_FIELDS = [
        'overview' => 'Project overview',
        'requirements' => 'Client requirements',
        'solution' => 'Design solution',
        'materials' => 'Materials & finishes',
    ];

    protected array $booleans = ['is_featured', 'is_published'];

    protected array $richText = ['overview', 'requirements', 'solution', 'materials'];

    protected ?string $slugFrom = 'title';

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($this->route('project'))],
            'project_category_id' => ['nullable', 'integer', 'exists:project_categories,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'overview' => ['nullable', 'string', 'max:100000'],
            'requirements' => ['nullable', 'string', 'max:100000'],
            'solution' => ['nullable', 'string', 'max:100000'],
            'materials' => ['nullable', 'string', 'max:100000'],
            'cover_media_id' => $this->mediaRule(),
            'before_media_id' => $this->mediaRule(),
            'after_media_id' => $this->mediaRule(),
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            ...$this->seoRules(canonical: false, og: false),
            'images' => ['nullable', 'array', 'max:80'],
            'images.*.media_id' => ['required', 'integer', 'exists:media,id'],
            'images.*.caption' => ['nullable', 'string', 'max:255'],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
        ];
    }

    protected function transform(array $data): array
    {
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return Arr::except($data, ['images', 'services']);
    }

    /** Project images in submitted order. */
    public function images(): array
    {
        return collect($this->validated('images') ?? [])->values()->map(fn ($row, $i) => [
            'media_id' => (int) $row['media_id'],
            'caption' => filled($row['caption'] ?? null) ? $row['caption'] : null,
            'sort_order' => $i,
        ])->all();
    }

    public function serviceIds(): array
    {
        return collect($this->validated('services') ?? [])->map(fn ($id) => (int) $id)->unique()->values()->all();
    }
}
