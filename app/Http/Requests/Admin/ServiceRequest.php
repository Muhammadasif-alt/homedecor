<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ServiceRequest extends AdminRequest
{
    /** JSON list columns edited with the repeater ({title, text} rows). */
    public const LISTS = [
        'included' => 'What’s included',
        'styles' => 'Styles we offer',
        'benefits' => 'Benefits',
        'materials' => 'Materials & finishes',
        'process' => 'Process (overrides the global steps)',
    ];

    private const RELATIONS = ['sections', 'gallery', 'related', 'projects'];

    protected array $booleans = ['is_featured', 'is_published'];

    protected array $richText = ['intro'];

    protected ?string $slugFrom = 'name';

    public function rules(): array
    {
        $service = $this->route('service');
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('services', 'slug')->ignore($service)],
            'icon' => ['nullable', 'string', 'max:40'],
            'short_description' => ['required', 'string', 'max:500'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['required', 'string', 'max:2000'],
            'hero_media_id' => $this->mediaRule(),
            'intro' => ['required', 'string', 'max:100000'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            ...$this->seoRules(canonical: true, og: true),

            'sections' => ['nullable', 'array', 'max:30'],
            'sections.*.id' => ['nullable', 'integer'],
            'sections.*.heading' => ['required', 'string', 'max:255'],
            'sections.*.body' => ['required', 'string', 'max:100000'],
            'sections.*.media_id' => $this->mediaRule(),
            'gallery' => ['nullable', 'array', 'max:60'],
            'gallery.*' => ['integer', 'exists:media,id'],
            'related' => ['nullable', 'array'],
            'related.*' => ['integer', Rule::exists('services', 'id')->whereNot('id', $service?->id ?? 0)],
            'projects' => ['nullable', 'array'],
            'projects.*' => ['integer', 'exists:projects,id'],
        ];
        foreach (array_keys(self::LISTS) as $list) {
            $rules += $this->rowRules($list, ['title', 'text'], 2000);
        }

        return $rules;
    }

    public function attributes(): array
    {
        return parent::attributes() + [
            'sections.*.heading' => 'section heading',
            'sections.*.body' => 'section text',
            'hero_media_id' => 'hero image',
        ];
    }

    /** Column data for the services table. */
    protected function transform(array $data): array
    {
        foreach (array_keys(self::LISTS) as $list) {
            $data[$list] = static::rows($data[$list] ?? [], ['title', 'text']) ?: null;
        }
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return Arr::except($data, self::RELATIONS);
    }

    /** Content sections in submitted order, with purified bodies. */
    public function sections(): array
    {
        return collect($this->validated('sections') ?? [])->values()->map(fn ($row, $i) => [
            'id' => isset($row['id']) ? (int) $row['id'] : null,
            'heading' => $row['heading'],
            'body' => static::purify($row['body']),
            'media_id' => $row['media_id'] ?? null,
            'sort_order' => $i,
        ])->all();
    }

    public function ids(string $key): array
    {
        return collect($this->validated($key) ?? [])->map(fn ($id) => (int) $id)->unique()->values()->all();
    }
}
