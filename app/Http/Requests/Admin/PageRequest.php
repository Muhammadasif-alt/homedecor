<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class PageRequest extends AdminRequest
{
    public const TEMPLATES = ['default' => 'Sections (default)', 'legal' => 'Legal / simple text page'];

    protected array $booleans = ['robots_index', 'is_published'];

    protected array $richText = ['body'];

    protected ?string $slugFrom = 'title';

    public function rules(): array
    {
        $page = $this->route('page');
        $templates = array_keys(self::TEMPLATES);
        if ($page && ! in_array($page->template, $templates, true)) {
            $templates[] = $page->template; // keep custom templates set by developers
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('pages', 'slug')->ignore($page)],
            'template' => ['required', Rule::in($templates)],
            'body' => ['nullable', 'string', 'max:200000'],
            'is_published' => ['boolean'],
            ...$this->seoRules(canonical: true, og: true, robots: true),
        ];
    }
}
