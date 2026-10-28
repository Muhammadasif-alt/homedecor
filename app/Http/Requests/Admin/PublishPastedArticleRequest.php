<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class PublishPastedArticleRequest extends AdminRequest
{
    protected ?string $slugFrom = 'title';

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:300000'],
            'post_id' => ['nullable', 'integer', 'exists:blog_posts,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts', 'slug')->ignore($this->input('post_id'))],
            'excerpt' => ['required', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:120'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'status' => ['required', Rule::in(array_keys(BlogPostRequest::STATUSES))],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:blog_categories,id'],
            'slots' => ['nullable', 'array', 'max:50'],
            'slots.*.media_id' => $this->mediaRule(),
            'slots.*.caption' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Another article already uses this web address. Change the slug.',
            'excerpt.required' => 'Add a short summary for the article cards.',
            'slots.*.media_id.exists' => 'One of the chosen photos no longer exists in the media library.',
        ];
    }

    public function attributes(): array
    {
        return parent::attributes() + ['slots.*.caption' => 'photo caption'];
    }

    /** @return array<int, int> */
    public function categoryIds(): array
    {
        return collect($this->validated('categories') ?? [])->map(fn ($id) => (int) $id)->unique()->values()->all();
    }
}
