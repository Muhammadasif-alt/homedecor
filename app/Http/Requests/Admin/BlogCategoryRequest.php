<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class BlogCategoryRequest extends AdminRequest
{
    protected ?string $slugFrom = 'name';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_categories', 'slug')->ignore($this->route('blogCategory'))],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            ...$this->seoRules(canonical: false, og: false),
        ];
    }

    protected function transform(array $data): array
    {
        return ['sort_order' => (int) ($data['sort_order'] ?? 0)] + $data;
    }
}
